<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{
    public function index(): Response
    {
        $branchId = $this->getBranchId();

        $purchaseOrders = PurchaseOrder::query()
            ->with(['supplier', 'creator', 'items.product', 'branch'])
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when(request('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->when(request('supplier_id'), function ($query, $supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->when(request('date_from'), function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when(request('date_to'), function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('PurchaseOrders/Index', [
            'purchaseOrders' => $purchaseOrders,
            'suppliers' => Supplier::orderBy('name')->get(['id', 'name']),
            'filters' => request()->only('status', 'supplier_id', 'date_from', 'date_to'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('PurchaseOrders/Create', [
            'suppliers' => Supplier::orderBy('name')->get(['id', 'name']),
            'products' => Product::where('status', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'cost_price'])
                ->loadSum('branches as stock', 'branch_product.stock'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn ($item) => $item['qty'] * $item['cost_price']);

        $purchaseOrder = DB::transaction(function () use ($validated, $total) {
            $todayCount = PurchaseOrder::whereDate('created_at', today())->lockForUpdate()->count() + 1;
            $poNumber = 'PO-'.now()->format('Ymd').'-'.str_pad($todayCount, 4, '0', STR_PAD_LEFT);

            $po = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $validated['supplier_id'],
                'status' => 'draft',
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
                'branch_id' => $this->getBranchId(),
            ]);

            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'cost_price' => $item['cost_price'],
                    'subtotal' => $item['qty'] * $item['cost_price'],
                ]);
            }

            return $po;
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function show(PurchaseOrder $purchaseOrder): Response
    {
        $purchaseOrder->load(['supplier', 'creator', 'approver', 'items.product', 'branch']);

        return Inertia::render('PurchaseOrders/Show', [
            'purchaseOrder' => $purchaseOrder,
            'canApprove' => auth()->user()->role !== 'kasir',
            'canReceive' => auth()->user()->role !== 'kasir',
        ]);
    }

    public function approve(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Hanya PO dengan status Draft yang bisa disetujui.');
        }

        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase Order berhasil disetujui.');
    }

    public function receive(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Hanya PO dengan status Approved yang bisa diterima.');
        }

        DB::beginTransaction();

        try {
            $purchaseOrder->load('items.product');
            $branchId = $this->getBranchId();

            $stockIn = StockIn::create([
                'supplier_id' => $purchaseOrder->supplier_id,
                'invoice_number' => $purchaseOrder->po_number,
                'notes' => 'Auto-generated from PO: '.$purchaseOrder->po_number,
                'created_by' => auth()->id(),
                'branch_id' => $branchId ?? $purchaseOrder->branch_id,
            ]);

            foreach ($purchaseOrder->items as $item) {
                $product = $item->product;

                StockInItem::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'cost_price' => $item->cost_price,
                    'subtotal' => $item->subtotal,
                ]);

                $branchIdToUse = $branchId ?? $purchaseOrder->branch_id;

                $beforeStock = $this->getProductStock($product, $branchIdToUse);
                $this->incrementProductStock($product, $item->qty, $branchIdToUse);

                StockMovement::create([
                    'product_id' => $item->product_id,
                    'type' => 'in',
                    'quantity' => $item->qty,
                    'before_stock' => $beforeStock,
                    'after_stock' => $this->getProductStock($product, $branchIdToUse),
                    'reference_type' => 'purchase_order',
                    'reference_id' => $purchaseOrder->id,
                    'user_id' => auth()->id(),
                    'branch_id' => $branchIdToUse,
                ]);
            }

            $purchaseOrder->update([
                'status' => 'received',
                'received_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('success', 'Purchase Order diterima. Stok berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function cancel(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if (! in_array($purchaseOrder->status, ['draft', 'approved'])) {
            return redirect()->back()
                ->with('error', 'PO yang sudah diterima/dibatalkan tidak bisa dibatalkan.');
        }

        $purchaseOrder->update(['status' => 'cancelled']);

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase Order dibatalkan.');
    }

    public function edit(PurchaseOrder $purchaseOrder): Response
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'Hanya PO draft yang bisa diedit.');
        }

        $purchaseOrder->load('items.product');

        return Inertia::render('PurchaseOrders/Create', [
            'purchaseOrder' => $purchaseOrder,
            'suppliers' => Supplier::orderBy('name')->get(['id', 'name']),
            'products' => Product::where('status', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'cost_price'])
                ->loadSum('branches as stock', 'branch_product.stock'),
        ]);
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'Hanya PO draft yang bisa diupdate.');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn ($item) => $item['qty'] * $item['cost_price']);

        DB::transaction(function () use ($purchaseOrder, $validated, $total) {
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
            ]);

            $purchaseOrder->items()->delete();

            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'cost_price' => $item['cost_price'],
                    'subtotal' => $item['qty'] * $item['cost_price'],
                ]);
            }
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase Order berhasil diupdate.');
    }

    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'Hanya PO draft yang bisa dihapus.');
        }

        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dihapus.');
    }
}
