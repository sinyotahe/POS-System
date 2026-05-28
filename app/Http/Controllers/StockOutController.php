<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockOut;
use App\Models\StockOutItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class StockOutController extends Controller
{
    public function index(): Response
    {
        $branchId = $this->getBranchId();

        $stockOuts = StockOut::query()
            ->with(['creator', 'items.product', 'branch'])
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when(request('type'), function ($query, $type) {
                $query->where('type', $type);
            })
            ->when(request('date_from'), function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when(request('date_to'), function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('StockOut/Index', [
            'stockOuts' => $stockOuts,
            'filters' => request()->only('type', 'date_from', 'date_to'),
        ]);
    }

    public function create(): Response
    {
        $branchId = $this->getBranchId();

        $products = Product::where('status', true)
            ->where(function ($q) use ($branchId) {
                if ($branchId) {
                    $q->whereHas('branches', function ($b) use ($branchId) {
                        $b->where('branch_id', $branchId)->where('stock', '>', 0);
                    });
                }
            })
            ->orderBy('name')
            ->get(['id', 'name', 'sku']);

        if ($branchId) {
            $products->each(function ($p) use ($branchId) {
                $bp = $p->branches()->where('branch_id', $branchId)->first();
                $p->stock = $bp ? (int) $bp->pivot->stock : 0;
            });
        } else {
            $products->loadSum('branches as stock', 'branch_product.stock');
        }

        return Inertia::render('StockOut/Create', [
            'products' => $products,
            'types' => [
                ['value' => 'rusak', 'label' => 'Rusak'],
                ['value' => 'hilang', 'label' => 'Hilang'],
                ['value' => 'retur_supplier', 'label' => 'Retur Supplier'],
                ['value' => 'pemakaian_internal', 'label' => 'Pemakaian Internal'],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:rusak,hilang,retur_supplier,pemakaian_internal',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $branchId = $this->getBranchId();

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $currentStock = $this->getProductStock($product);

                if ($currentStock < $item['qty']) {
                    DB::rollBack();

                    return redirect()->back()
                        ->with('error', "Stok {$product->name} tidak mencukupi. Tersedia: {$currentStock}, diminta: {$item['qty']}.");
                }
            }

            $stockOut = StockOut::create([
                'type' => $validated['type'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
                'branch_id' => $branchId,
            ]);

            foreach ($validated['items'] as $item) {
                StockOutItem::create([
                    'stock_out_id' => $stockOut->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                ]);

                $product = Product::findOrFail($item['product_id']);
                $beforeStock = $this->getProductStock($product);
                $decremented = $this->decrementProductStock($product, $item['qty']);

                if (! $decremented) {
                    DB::rollBack();

                    return redirect()->back()
                        ->with('error', "Stok {$product->name} tidak mencukupi saat diproses. Silakan coba lagi.");
                }

                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'type' => 'out',
                    'quantity' => $item['qty'],
                    'before_stock' => $beforeStock,
                    'after_stock' => $beforeStock - $item['qty'],
                    'reference_type' => 'stock_out',
                    'reference_id' => $stockOut->id,
                    'user_id' => auth()->id(),
                    'branch_id' => $branchId,
                ]);
            }

            DB::commit();

            return redirect()->route('stock-outs.show', $stockOut)
                ->with('success', 'Stok keluar berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function show(StockOut $stockOut): Response
    {
        $stockOut->load(['creator', 'items.product', 'branch']);

        return Inertia::render('StockOut/Show', [
            'stockOut' => $stockOut,
        ]);
    }
}
