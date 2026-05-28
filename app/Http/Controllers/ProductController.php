<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        $branchId = $this->getBranchId();

        $products = Product::query()
            ->with('category')
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->when(request('category_id'), function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when(request('status') !== null, function ($query) {
                $query->where('status', request('status'));
            })
            ->orderBy('name')
            ->paginate(10);

        if ($branchId) {
            $products->load(['branches' => fn ($q) => $q->where('branch_id', $branchId)]);
            $products->each(function ($p) {
                $bp = $p->branches->first();
                $p->stock = $bp ? (int) $bp->pivot->stock : 0;
            });
        } else {
            $products->loadSum('branches as stock', 'branch_product.stock');
        }

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'filters' => request()->only('search', 'category_id', 'status'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Products/Create', [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'branches' => Branch::where('status', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:50|unique:products,barcode',
            'name' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'boolean',
            'branch_ids' => 'nullable|array',
            'branch_ids.*' => 'exists:branches,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['status'] = $request->boolean('status');

        $product = Product::create($validated);

        $branchIds = $request->input('branch_ids', []);
        if (empty($branchIds)) {
            $branchId = $this->getBranchId();
            if ($branchId) {
                $branchIds = [$branchId];
            }
        }

        foreach ($branchIds as $bid) {
            DB::table('branch_product')->updateOrInsert(
                ['branch_id' => $bid, 'product_id' => $product->id],
                ['stock' => 0, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        ActivityLog::log('create', 'product', $product->id, "Produk {$product->name} ditambahkan");

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): Response
    {
        $product->load('branches');

        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'branches' => Branch::where('status', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|max:50|unique:products,sku,'.$product->id,
            'barcode' => 'nullable|string|max:50|unique:products,barcode,'.$product->id,
            'name' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'boolean',
            'branch_ids' => 'nullable|array',
            'branch_ids.*' => 'exists:branches,id',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
        }

        $validated['status'] = $request->boolean('status');

        $product->update($validated);

        if ($request->has('branch_ids')) {
            $branchIds = $request->branch_ids;
            $existingBranches = $product->branches()->pluck('branches.id')->toArray();

            foreach ($branchIds as $branchId) {
                if (! in_array($branchId, $existingBranches)) {
                    DB::table('branch_product')->updateOrInsert(
                        ['branch_id' => $branchId, 'product_id' => $product->id],
                        ['stock' => 0, 'created_at' => now(), 'updated_at' => now()]
                    );
                }
            }

            foreach ($existingBranches as $existingId) {
                if (! in_array($existingId, $branchIds)) {
                    DB::table('branch_product')
                        ->where('branch_id', $existingId)
                        ->where('product_id', $product->id)
                        ->delete();
                }
            }

            $product->load('branches');
        }

        ActivityLog::log('update', 'product', $product->id, "Produk {$product->name} diubah");

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function barcodePrint(): Response
    {
        $products = Product::where('status', true)
            ->whereNotNull('barcode')
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'barcode', 'sell_price']);

        return Inertia::render('Products/BarcodePrint', [
            'products' => $products,
        ]);
    }

    public function toggleStatus(Product $product): RedirectResponse
    {
        $product->update(['status' => ! $product->status]);

        ActivityLog::log('update', 'product', $product->id, "Status produk {$product->name} diubah");

        return redirect()->back()->with('success', 'Status produk berhasil diubah.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        ActivityLog::log('delete', 'product', $product->id, "Produk {$name} dihapus");

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
