<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class StockTransferController extends Controller
{
    public function index(): Response
    {
        $branchId = $this->getBranchId();

        $transfers = StockTransfer::with(['fromBranch', 'toBranch', 'product', 'creator'])
            ->when($branchId, function ($q) use ($branchId) {
                $q->where(function ($query) use ($branchId) {
                    $query->where('from_branch_id', $branchId)
                        ->orWhere('to_branch_id', $branchId);
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('StockTransfers/Index', [
            'transfers' => $transfers,
        ]);
    }

    public function create(): Response
    {
        $branchId = $this->getBranchId();

        $branches = Branch::where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $products = Product::where('status', true)
            ->whereHas('branches', fn ($q) => $q->where('branch_id', $branchId)->where('stock', '>', 0))
            ->orderBy('name')
            ->get(['id', 'name', 'sku']);

        $products->each(function ($p) use ($branchId) {
            $bp = $p->branches()->where('branch_id', $branchId)->first();
            $p->stock = $bp ? (int) $bp->pivot->stock : 0;
        });

        return Inertia::render('StockTransfers/Create', [
            'branches' => $branches,
            'products' => $products,
            'currentBranchId' => $branchId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $branchId = $this->getBranchId();

        if (! $branchId) {
            return redirect()->back()
                ->with('error', 'Pilih cabang terlebih dahulu untuk melakukan transfer.');
        }

        $validated['from_branch_id'] = $branchId;

        if ($validated['from_branch_id'] === (int) $validated['to_branch_id']) {
            return redirect()->back()
                ->with('error', 'Cabang asal dan tujuan harus berbeda.');
        }

        $product = Product::findOrFail($validated['product_id']);
        $currentStock = $this->getProductStock($product, $branchId);

        if ($currentStock < $validated['qty']) {
            return redirect()->back()
                ->with('error', "Stok {$product->name} di cabang asal tidak mencukupi. Tersedia: {$currentStock}.");
        }

        DB::beginTransaction();

        try {
            StockTransfer::create([
                'from_branch_id' => $validated['from_branch_id'],
                'to_branch_id' => $validated['to_branch_id'],
                'product_id' => $validated['product_id'],
                'qty' => $validated['qty'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $beforeFrom = $this->getProductStock($product, $validated['from_branch_id']);
            $this->decrementProductStock($product, $validated['qty'], $validated['from_branch_id']);

            $beforeTo = $this->getProductStock($product, $validated['to_branch_id']);
            $this->incrementProductStock($product, $validated['qty'], $validated['to_branch_id']);

            StockMovement::create([
                'product_id' => $validated['product_id'],
                'type' => 'out',
                'quantity' => $validated['qty'],
                'before_stock' => $beforeFrom,
                'after_stock' => $beforeFrom - $validated['qty'],
                'reference_type' => 'stock_transfer',
                'reference_id' => 0,
                'user_id' => auth()->id(),
                'branch_id' => $validated['from_branch_id'],
            ]);

            StockMovement::create([
                'product_id' => $validated['product_id'],
                'type' => 'in',
                'quantity' => $validated['qty'],
                'before_stock' => $beforeTo,
                'after_stock' => $beforeTo + $validated['qty'],
                'reference_type' => 'stock_transfer',
                'reference_id' => 0,
                'user_id' => auth()->id(),
                'branch_id' => $validated['to_branch_id'],
            ]);

            DB::commit();

            return redirect()->route('stock-transfers.index')
                ->with('success', "Transfer stok {$product->name} berhasil diproses.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
