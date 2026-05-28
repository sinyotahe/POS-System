<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class StockInController extends Controller
{
    public function index(): Response
    {
        $branchId = $this->getBranchId();

        $stockIns = StockIn::query()
            ->with(['supplier', 'creator', 'items.product', 'branch'])
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
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

        return Inertia::render('StockIn/Index', [
            'stockIns' => $stockIns,
            'suppliers' => Supplier::orderBy('name')->get(['id', 'name']),
            'filters' => request()->only('supplier_id', 'date_from', 'date_to'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('StockIn/Create', [
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
            'invoice_number' => 'nullable|string|max:100',
            'invoice_image' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $branchId = $this->getBranchId();

            $invoiceImage = null;
            if ($request->hasFile('invoice_image')) {
                $invoiceImage = $request->file('invoice_image')->store('stock-invoices', 'public');
            }

            $stockIn = StockIn::create([
                'supplier_id' => $validated['supplier_id'],
                'invoice_number' => $validated['invoice_number'] ?? null,
                'invoice_image' => $invoiceImage,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
                'branch_id' => $branchId,
            ]);

            foreach ($validated['items'] as $item) {
                $subtotal = $item['qty'] * $item['cost_price'];

                StockInItem::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'cost_price' => $item['cost_price'],
                    'subtotal' => $subtotal,
                ]);

                $product = Product::findOrFail($item['product_id']);
                $beforeStock = $this->getProductStock($product);

                $this->incrementProductStock($product, $item['qty']);

                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'type' => 'in',
                    'quantity' => $item['qty'],
                    'before_stock' => $beforeStock,
                    'after_stock' => $this->getProductStock($product),
                    'reference_type' => 'stock_in',
                    'reference_id' => $stockIn->id,
                    'user_id' => auth()->id(),
                    'branch_id' => $branchId,
                ]);
            }

            DB::commit();

            return redirect()->route('stock-ins.show', $stockIn)
                ->with('success', 'Stok masuk berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function show(StockIn $stockIn): Response
    {
        $stockIn->load(['supplier', 'creator', 'items.product', 'branch']);

        return Inertia::render('StockIn/Show', [
            'stockIn' => $stockIn,
        ]);
    }
}
