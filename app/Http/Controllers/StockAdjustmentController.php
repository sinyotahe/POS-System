<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class StockAdjustmentController extends Controller
{
    public function create(): Response
    {
        $branchId = $this->getBranchId();

        $products = Product::where('status', true)
            ->orderBy('name')
            ->when($branchId, function ($q) use ($branchId) {
                $q->whereHas('branches', function ($b) use ($branchId) {
                    $b->where('branch_id', $branchId);
                });
            })
            ->get(['id', 'name', 'sku']);

        if ($branchId) {
            $products->each(function ($p) use ($branchId) {
                $bp = $p->branches()->where('branch_id', $branchId)->first();
                $p->stock = $bp ? (int) $bp->pivot->stock : 0;
            });
        } else {
            $products->loadSum('branches as stock', 'branch_product.stock');
        }

        return Inertia::render('StockAdjustments/Create', [
            'products' => $products,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|not_in:0',
            'reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($validated['product_id']);
            $qty = (int) $validated['qty'];
            $branchId = $this->getBranchId();
            $beforeStock = $this->getProductStock($product);

            if ($qty > 0) {
                $this->incrementProductStock($product, $qty);
            } else {
                $absQty = abs($qty);
                if ($beforeStock < $absQty) {
                    DB::rollBack();

                    return redirect()->back()
                        ->with('error', "Stok {$product->name} tidak mencukupi. Tersedia: {$beforeStock}, akan dikurangi: {$absQty}.");
                }

                $decremented = $this->decrementProductStock($product, $absQty);
                if (! $decremented) {
                    DB::rollBack();

                    return redirect()->back()
                        ->with('error', "Stok {$product->name} tidak mencukupi saat diproses.");
                }
            }

            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'adjustment',
                'quantity' => $qty,
                'before_stock' => $beforeStock,
                'after_stock' => $beforeStock + $qty,
                'reference_type' => 'adjustment',
                'user_id' => auth()->id(),
                'branch_id' => $branchId,
            ]);

            DB::commit();

            return redirect()->route('stock-movements.index')
                ->with('success', "Stok {$product->name} berhasil disesuaikan.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
