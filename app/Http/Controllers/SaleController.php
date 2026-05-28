<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SaleController extends Controller
{
    public function index(): Response
    {
        $branchId = $this->getBranchId();

        $sales = Sale::with('cashier', 'branch')
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when(auth()->user()->role === 'kasir', fn ($q) => $q->where('cashier_id', auth()->id()))
            ->when(request('date_from'), function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when(request('date_to'), function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->when(request('payment_method'), function ($query, $method) {
                $query->where('payment_method', $method);
            })
            ->when(request('cashier_id'), function ($query, $cashierId) {
                $query->where('cashier_id', $cashierId);
            })
            ->when(request('voided') !== 'yes', function ($query) {
                $query->whereNull('voided_at');
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('Sales/Index', [
            'sales' => $sales,
            'cashiers' => User::whereIn('role', ['admin', 'kasir'])->get(['id', 'name']),
            'filters' => request()->only('date_from', 'date_to', 'payment_method', 'cashier_id'),
        ]);
    }

    public function show(Sale $sale): Response
    {
        if (auth()->user()->role === 'kasir' && $sale->cashier_id !== auth()->id()) {
            abort(403);
        }

        $sale->load(['items.product', 'cashier', 'branch']);

        return Inertia::render('Sales/Show', [
            'sale' => $sale,
        ]);
    }

    public function print(Sale $sale): Response
    {
        if (auth()->user()->role === 'kasir' && $sale->cashier_id !== auth()->id()) {
            abort(403);
        }

        $sale->load(['items.product', 'cashier', 'branch']);

        return Inertia::render('Sales/Print', [
            'sale' => $sale,
        ]);
    }

    public function void(Request $request, Sale $sale): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        if ($sale->isVoided()) {
            return redirect()->back()->with('error', 'Transaksi sudah divoid sebelumnya.');
        }

        DB::beginTransaction();

        try {
            $sale->load('items.product');

            foreach ($sale->items as $item) {
                $product = $item->product;
                $beforeStock = $this->getProductStock($product, $sale->branch_id);
                $this->incrementProductStock($product, $item->qty, $sale->branch_id);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'void',
                    'quantity' => $item->qty,
                    'before_stock' => $beforeStock,
                    'after_stock' => $beforeStock + $item->qty,
                    'reference_type' => 'sale',
                    'reference_id' => $sale->id,
                    'user_id' => auth()->id(),
                    'branch_id' => $sale->branch_id,
                ]);
            }

            $sale->update([
                'voided_at' => now(),
                'voided_by' => auth()->id(),
                'void_reason' => $validated['reason'] ?? null,
            ]);

            DB::commit();

            ActivityLog::log('void', 'sale', $sale->id, "Transaksi {$sale->invoice_number} dibatalkan");

            return redirect()->back()->with('success', 'Transaksi berhasil divoid.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
