<?php

namespace App\Http\Controllers;

use App\Models\HeldCart;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Setting;
use App\Models\StockMovement;
use App\Notifications\SaleReceiptNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Inertia\Response;

class PosController extends Controller
{
    protected function getBranchProductQuery()
    {
        $branchId = $this->getBranchId();

        return Product::with('category')
            ->where('status', true)
            ->when($branchId, function ($q) use ($branchId) {
                $q->whereHas('branches', function ($b) use ($branchId) {
                    $b->where('branch_id', $branchId);
                });
            });
    }

    public function index(): Response
    {
        $products = $this->getBranchProductQuery()
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'barcode', 'sell_price', 'image', 'category_id']);

        // Attach branch stock for display
        $branchId = $this->getBranchId();
        if ($branchId) {
            $products->each(function ($p) use ($branchId) {
                $bp = $p->branches()->where('branch_id', $branchId)->first();
                $p->stock = $bp ? (int) $bp->pivot->stock : 0;
            });
        } else {
            $products->loadSum('branches as stock', 'branch_product.stock');
        }

        return Inertia::render('POS/Index', [
            'products' => $products,
        ]);
    }

    public function searchProducts(Request $request): JsonResponse
    {
        $search = $request->get('q');
        $branchId = $this->getBranchId();

        $products = Product::with('category')
            ->where('status', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->when($branchId, function ($q) use ($branchId) {
                $q->whereHas('branches', function ($b) use ($branchId) {
                    $b->where('branch_id', $branchId);
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'sku', 'barcode', 'sell_price', 'image', 'category_id']);

        if ($branchId) {
            $products->each(function ($p) use ($branchId) {
                $bp = $p->branches()->where('branch_id', $branchId)->first();
                $p->stock = $bp ? (int) $bp->pivot->stock : 0;
            });
        } else {
            $products->loadSum('branches as stock', 'branch_product.stock');
        }

        return response()->json($products);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.item_discount' => 'nullable|numeric|min:0|max:100',
            'customer_name' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|in:cash,transfer,qris,e-wallet',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;
            $items = [];
            $branchId = $this->getBranchId();

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $currentStock = $this->getProductStock($product);

                if ($currentStock < $item['qty']) {
                    DB::rollBack();

                    return redirect()->back()
                        ->with('error', "Stok {$product->name} tidak mencukupi. Tersedia: {$currentStock}, diminta: {$item['qty']}.");
                }

                $itemDiscount = $item['item_discount'] ?? 0;
                $subtotal = $product->sell_price * $item['qty'];
                $itemDiscountAmount = round($subtotal * $itemDiscount / 100 * 100) / 100;
                $total += $subtotal - $itemDiscountAmount;

                $items[] = [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'price' => $product->sell_price,
                    'item_discount' => $itemDiscountAmount,
                    'subtotal' => $subtotal - $itemDiscountAmount,
                ];
            }

            $discount = $validated['discount'] ?? 0;
            $taxRate = (float) (Setting::get('tax_rate') ?? config('app.tax_rate'));
            $tax = round($total * $taxRate * 100) / 100;
            $grandTotal = $total - $discount + $tax;
            $paidAmount = $validated['paid_amount'];
            $changeAmount = max(0, $paidAmount - $grandTotal);

            if ($validated['payment_method'] === 'cash' && $paidAmount < $grandTotal) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Jumlah dibayar tunai tidak boleh kurang dari grand total (Rp '.number_format($grandTotal, 0, ',', '.').').');
            }

            $todayCount = Sale::whereDate('created_at', today())->lockForUpdate()->count() + 1;
            $invoiceNumber = 'INV-'.date('Ymd').'-'.str_pad($todayCount, 4, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_name' => $validated['customer_name'] ?? null,
                'total' => $total,
                'discount' => $discount,
                'tax' => $tax,
                'tax_rate' => $taxRate,
                'grand_total' => $grandTotal,
                'payment_method' => $validated['payment_method'],
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'cashier_id' => auth()->id(),
                'branch_id' => $branchId,
            ]);

            foreach ($items as $item) {
                $product = $item['product'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'discount' => $item['item_discount'],
                ]);

                $beforeStock = $this->getProductStock($product);
                $decremented = $this->decrementProductStock($product, $item['qty']);

                if (! $decremented) {
                    DB::rollBack();

                    return redirect()->back()
                        ->with('error', "Stok {$product->name} tidak mencukupi saat diproses. Silakan coba lagi.");
                }

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity' => $item['qty'],
                    'before_stock' => $beforeStock,
                    'after_stock' => $beforeStock - $item['qty'],
                    'reference_type' => 'sale',
                    'reference_id' => $sale->id,
                    'user_id' => auth()->id(),
                    'branch_id' => $branchId,
                ]);
            }

            DB::commit();

            // Send WhatsApp receipt if enabled
            if (Setting::get('whatsapp_notify_sale_receipt') === '1') {
                $receiptPhone = Setting::get('whatsapp_phone');
                if ($receiptPhone) {
                    $notifiable = new class($receiptPhone) implements Notifiable
                    {
                        use Notifiable;

                        public function __construct(public string $phone) {}

                        public function routeNotificationForWhatsApp(): string
                        {
                            return $this->phone;
                        }

                        public function getKey(): mixed
                        {
                            return null;
                        }
                    };
                    Notification::send($notifiable, new SaleReceiptNotification($sale));
                }
            }

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Transaksi berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function hold(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.item_discount' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'label' => 'nullable|string|max:255',
            'customer_name' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|in:cash,transfer,qris,e-wallet',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        HeldCart::create([
            'user_id' => auth()->id(),
            'label' => $validated['label'] ?? null,
            'items' => collect($validated['items'])->map(fn ($i) => [
                'product_id' => $i['product_id'],
                'qty' => $i['qty'],
                'price' => $i['price'],
                'item_discount' => $i['item_discount'] ?? 0,
            ])->toArray(),
            'discount' => $validated['discount'] ?? 0,
            'customer_name' => $validated['customer_name'] ?? null,
            'payment_method' => $validated['payment_method'] ?? 'cash',
            'paid_amount' => $validated['paid_amount'] ?? 0,
            'branch_id' => $this->getBranchId(),
        ]);

        return redirect()->back()->with('success', 'Transaksi dihold.');
    }

    public function heldCarts(): JsonResponse
    {
        $branchId = $this->getBranchId();

        $carts = HeldCart::with('user')
            ->where('user_id', auth()->id())
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->orderByDesc('created_at')
            ->get();

        return response()->json($carts);
    }

    public function unhold(HeldCart $heldCart): RedirectResponse
    {
        abort_if($heldCart->user_id !== auth()->id(), 403);

        $heldCart->delete();

        return redirect()->back()->with('success', 'Cart di-restore.');
    }

    public function getCart(Request $request): JsonResponse
    {
        $cart = $request->session()->get('pos_cart', []);

        return response()->json($cart);
    }
}
