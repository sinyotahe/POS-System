<?php

namespace App\Http\Controllers;

use App\Exports\FinancialExport;
use App\Exports\InventoryExport;
use App\Exports\SalesExport;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(): Response
    {
        $branchId = $this->getBranchId();
        $user = auth()->user();

        $query = Sale::query()
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when($user->role === 'kasir', fn ($q) => $q->where('cashier_id', $user->id));
        $period = request('period', 'daily');
        $dateFrom = request('date_from');
        $dateTo = request('date_to');

        if ($period === 'daily') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'weekly') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $salesTotal = (float) $query->sum('grand_total');
        $salesCount = $query->count();

        $products = Product::with('category')
            ->when($branchId, fn ($q) => $q->whereHas('branches', fn ($b) => $b->where('branch_id', $branchId)))
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'minimum_stock', 'category_id']);

        if ($branchId) {
            $products->load(['branches' => fn ($q) => $q->where('branch_id', $branchId)]);
            $products->each(function ($p) {
                $bp = $p->branches->first();
                $p->stock = $bp ? (int) $bp->pivot->stock : 0;
            });
        } else {
            $products->loadSum('branches as stock', 'branch_product.stock');
        }

        $lowStockProducts = $products->filter(fn ($p) => $p->stock <= $p->minimum_stock)->values();

        $monthSales = SaleItem::whereHas('sale', function ($q) use ($branchId) {
            $q->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->when($branchId, fn ($b) => $b->where('branch_id', $branchId));
        })->select(DB::raw('SUM(subtotal) as total'))->first()->total ?? 0;

        $monthCost = SaleItem::whereHas('sale', function ($q) use ($branchId) {
            $q->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->when($branchId, fn ($b) => $b->where('branch_id', $branchId));
        })->select(DB::raw('SUM(qty * (SELECT cost_price FROM products WHERE products.id = sale_items.product_id)) as total_cost'))
            ->first()->total_cost ?? 0;

        $omzet = (float) Sale::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->sum('grand_total');

        $grossProfit = $monthSales - $monthCost;
        $profitMargin = $omzet > 0 ? round(($grossProfit / $omzet) * 100, 1) : 0;

        $userRole = $user->role;

        return Inertia::render('Reports/Index', [
            'salesSummary' => [
                'total' => $salesTotal,
                'count' => $salesCount,
                'data' => [],
            ],
            'products' => $products,
            'lowStockProducts' => $lowStockProducts,
            'financialSummary' => [
                'omzet' => $omzet,
                'gross_profit' => $grossProfit,
                'profit_margin' => $profitMargin,
            ],
            'filters' => request()->only('date_from', 'date_to', 'period'),
            'userRole' => $userRole,
        ]);
    }

    public function sales(): Response
    {
        $branchId = $this->getBranchId();
        $user = auth()->user();

        $query = Sale::query()
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when($user->role === 'kasir', fn ($q) => $q->where('cashier_id', $user->id));

        $period = request('period', 'daily');

        if ($period === 'daily') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'weekly') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }

        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        if (request('cashier_id')) {
            $query->where('cashier_id', request('cashier_id'));
        }

        $sales = $query->with('cashier')
            ->orderByDesc('created_at')
            ->paginate(10);

        $summary = Sale::query()
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when(request('date_from'), fn ($q) => $q->whereDate('created_at', '>=', request('date_from')))
            ->when(request('date_to'), fn ($q) => $q->whereDate('created_at', '<=', request('date_to')))
            ->when(request('cashier_id'), fn ($q) => $q->where('cashier_id', request('cashier_id')))
            ->selectRaw('COUNT(*) as total_transactions, COALESCE(SUM(grand_total), 0) as total_revenue')
            ->first();

        $productSales = SaleItem::select(
            'product_id',
            DB::raw('SUM(qty) as total_qty'),
            DB::raw('SUM(subtotal) as total_subtotal')
        )
            ->when(request('date_from'), function ($q) {
                $q->whereHas('sale', fn ($s) => $s->whereDate('created_at', '>=', request('date_from')));
            })
            ->when(request('date_to'), function ($q) {
                $q->whereHas('sale', fn ($s) => $s->whereDate('created_at', '<=', request('date_to')));
            })
            ->when(request('cashier_id'), function ($q) {
                $q->whereHas('sale', fn ($s) => $s->where('cashier_id', request('cashier_id')));
            })
            ->when($branchId, function ($q) use ($branchId) {
                $q->whereHas('sale', fn ($s) => $s->where('branch_id', $branchId));
            })
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        return Inertia::render('Reports/Sales', [
            'sales' => $sales,
            'summary' => $summary,
            'productSales' => $productSales,
            'cashiers' => $user->role === 'kasir' ? collect() : User::where('role', 'kasir')->orderBy('name')->get(['id', 'name']),
            'filters' => request()->only('period', 'date_from', 'date_to', 'cashier_id'),
            'userRole' => $user->role,
        ]);
    }

    public function inventory(): Response
    {
        $branchId = $this->getBranchId();

        $productsQuery = Product::with('category')
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            });

        if ($branchId) {
            $productsQuery
                ->join('branch_product', function ($join) use ($branchId) {
                    $join->on('products.id', '=', 'branch_product.product_id')
                        ->where('branch_product.branch_id', $branchId);
                })
                ->when(request('stock_status') === 'low', function ($query) {
                    $query->whereColumn('branch_product.stock', '<=', 'products.minimum_stock');
                })
                ->when(request('stock_status') === 'out', function ($query) {
                    $query->where('branch_product.stock', 0);
                })
                ->orderBy('branch_product.stock')
                ->select('products.*', 'branch_product.stock as stock');
        } else {
            $productsQuery
                ->join('branch_product', 'products.id', '=', 'branch_product.product_id')
                ->groupBy('products.id')
                ->when(request('stock_status') === 'low', function ($query) {
                    $query->havingRaw('COALESCE(SUM(branch_product.stock), 0) <= products.minimum_stock');
                })
                ->when(request('stock_status') === 'out', function ($query) {
                    $query->havingRaw('COALESCE(SUM(branch_product.stock), 0) = 0');
                })
                ->orderByRaw('COALESCE(SUM(branch_product.stock), 0)')
                ->select('products.*', DB::raw('COALESCE(SUM(branch_product.stock), 0) as stock'));
        }

        $products = $productsQuery->paginate(20);

        $recentMovements = StockMovement::with(['product', 'user', 'branch'])
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->latest('created_at')
            ->limit(20)
            ->get();

        $summary = [
            'total_products' => Product::when($branchId, fn ($q) => $q->whereHas('branches', fn ($b) => $b->where('branch_id', $branchId)))->count(),
            'active_products' => Product::where('status', true)->when($branchId, fn ($q) => $q->whereHas('branches', fn ($b) => $b->where('branch_id', $branchId)))->count(),
            'low_stock' => $branchId
                ? DB::table('products')
                    ->join('branch_product', fn ($j) => $j->on('products.id', '=', 'branch_product.product_id')->where('branch_product.branch_id', $branchId))
                    ->whereColumn('branch_product.stock', '<=', 'products.minimum_stock')
                    ->count()
                : Product::join('branch_product', 'products.id', '=', 'branch_product.product_id')
                    ->groupBy('products.id')
                    ->havingRaw('COALESCE(SUM(branch_product.stock), 0) <= products.minimum_stock')
                    ->count(),
            'out_of_stock' => $branchId
                ? DB::table('products')
                    ->join('branch_product', fn ($j) => $j->on('products.id', '=', 'branch_product.product_id')->where('branch_product.branch_id', $branchId))
                    ->where('branch_product.stock', 0)
                    ->count()
                : Product::join('branch_product', 'products.id', '=', 'branch_product.product_id')
                    ->groupBy('products.id')
                    ->havingRaw('COALESCE(SUM(branch_product.stock), 0) = 0')
                    ->count(),
            'total_stock_value' => $branchId
                ? DB::table('products')
                    ->join('branch_product', fn ($j) => $j->on('products.id', '=', 'branch_product.product_id')->where('branch_product.branch_id', $branchId))
                    ->select(DB::raw('COALESCE(SUM(branch_product.stock * products.cost_price), 0) as value'))
                    ->first()->value
                : DB::table('products')
                    ->join('branch_product', 'products.id', '=', 'branch_product.product_id')
                    ->select(DB::raw('COALESCE(SUM(branch_product.stock * products.cost_price), 0) as value'))
                    ->first()->value,
        ];

        return Inertia::render('Reports/Inventory', [
            'products' => $products,
            'recentMovements' => $recentMovements,
            'summary' => $summary,
            'filters' => request()->only('search', 'stock_status'),
        ]);
    }

    public function financial(): Response
    {
        $branchId = $this->getBranchId();
        $dateFrom = request('date_from', now()->startOfMonth()->toDateString());
        $dateTo = request('date_to', now()->toDateString());

        $sales = Sale::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->get();

        $omzet = $sales->sum('grand_total');

        $costOfGoodsSold = SaleItem::whereHas('sale', function ($q) use ($dateFrom, $dateTo, $branchId) {
            $q->whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->when($branchId, fn ($b) => $b->where('branch_id', $branchId));
        })->select(
            DB::raw('SUM(qty * (SELECT cost_price FROM products WHERE products.id = sale_items.product_id)) as total_cost')
        )->first()->total_cost ?? 0;

        $totalSalesValue = SaleItem::whereHas('sale', function ($q) use ($dateFrom, $dateTo, $branchId) {
            $q->whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->when($branchId, fn ($b) => $b->where('branch_id', $branchId));
        })->select(DB::raw('SUM(subtotal) as total'))->first()->total ?? 0;

        $grossProfit = $totalSalesValue - $costOfGoodsSold;
        $estimatedProfit = $grossProfit - $sales->sum('discount');

        $dailyTotals = Sale::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(grand_total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Reports/Financial', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'omzet' => $omzet,
            'grossProfit' => $grossProfit,
            'estimatedProfit' => $estimatedProfit,
            'costOfGoodsSold' => $costOfGoodsSold,
            'dailyTotals' => $dailyTotals,
            'totalSales' => $sales->count(),
            'filters' => request()->only('date_from', 'date_to'),
        ]);
    }

    public function branchComparison(): Response
    {
        $dateFrom = request('date_from', now()->startOfMonth()->toDateString());
        $dateTo = request('date_to', now()->toDateString());

        $branches = Branch::where('status', true)->orderBy('name')->get(['id', 'name']);
        $branchData = [];

        foreach ($branches as $branch) {
            $sales = Sale::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->where('branch_id', $branch->id)
                ->get();

            $totalSalesValue = SaleItem::whereHas('sale', function ($q) use ($dateFrom, $dateTo, $branch) {
                $q->whereDate('created_at', '>=', $dateFrom)
                    ->whereDate('created_at', '<=', $dateTo)
                    ->where('branch_id', $branch->id);
            })->select(DB::raw('SUM(subtotal) as total'))->first()->total ?? 0;

            $costOfGoodsSold = SaleItem::whereHas('sale', function ($q) use ($dateFrom, $dateTo, $branch) {
                $q->whereDate('created_at', '>=', $dateFrom)
                    ->whereDate('created_at', '<=', $dateTo)
                    ->where('branch_id', $branch->id);
            })->select(
                DB::raw('SUM(qty * (SELECT cost_price FROM products WHERE products.id = sale_items.product_id)) as total_cost')
            )->first()->total_cost ?? 0;

            $omzet = $sales->sum('grand_total');
            $grossProfit = $totalSalesValue - $costOfGoodsSold;
            $profitMargin = $omzet > 0 ? round(($grossProfit / $omzet) * 100, 1) : 0;

            $branchData[] = [
                'name' => $branch->name,
                'transactions' => $sales->count(),
                'omzet' => $omzet,
                'gross_profit' => $grossProfit,
                'profit_margin' => $profitMargin,
            ];
        }

        return Inertia::render('Reports/BranchComparison', [
            'branchData' => $branchData,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'filters' => request()->only('date_from', 'date_to'),
        ]);
    }

    public function exportSales(string $format): BinaryFileResponse|\Illuminate\Http\Response
    {
        $branchId = $this->getBranchId();

        $export = new SalesExport(
            dateFrom: request('date_from'),
            dateTo: request('date_to'),
            period: request('period'),
            cashierId: request('cashier_id') ? (int) request('cashier_id') : null,
            branchId: $branchId,
        );

        if ($format === 'pdf') {
            $sales = Sale::with('cashier')
                ->when(request('date_from'), fn ($q) => $q->whereDate('created_at', '>=', request('date_from')))
                ->when(request('date_to'), fn ($q) => $q->whereDate('created_at', '<=', request('date_to')))
                ->when(request('period') === 'daily', fn ($q) => $q->whereDate('created_at', today()))
                ->when(request('period') === 'weekly', fn ($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
                ->when(request('period') === 'monthly', fn ($q) => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year))
                ->when(request('cashier_id'), fn ($q) => $q->where('cashier_id', request('cashier_id')))
                ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
                ->orderByDesc('created_at')
                ->get();

            $pdf = Pdf::loadView('exports.sales-pdf', [
                'sales' => $sales,
                'dateFrom' => request('date_from'),
                'dateTo' => request('date_to'),
            ]);

            return $pdf->download('laporan-penjualan.pdf');
        }

        return Excel::download($export, 'laporan-penjualan.xlsx');
    }

    public function exportInventory(string $format): BinaryFileResponse|\Illuminate\Http\Response
    {
        $branchId = $this->getBranchId();

        $export = new InventoryExport(
            search: request('search'),
            stockStatus: request('stock_status'),
            branchId: $branchId,
        );

        if ($format === 'pdf') {
            $productsQuery = Product::with('category')
                ->when(request('search'), fn ($q) => $q->where(function ($q) {
                    $q->where('name', 'like', '%'.request('search').'%')
                        ->orWhere('sku', 'like', '%'.request('search').'%');
                }));

            if ($branchId) {
                $productsQuery
                    ->join('branch_product', fn ($j) => $j->on('products.id', '=', 'branch_product.product_id')->where('branch_product.branch_id', $branchId))
                    ->when(request('stock_status') === 'low', fn ($q) => $q->whereColumn('branch_product.stock', '<=', 'minimum_stock'))
                    ->when(request('stock_status') === 'out', fn ($q) => $q->where('branch_product.stock', 0))
                    ->select('products.*', 'branch_product.stock as stock');
            } else {
                $productsQuery
                    ->join('branch_product', 'products.id', '=', 'branch_product.product_id')
                    ->groupBy('products.id')
                    ->when(request('stock_status') === 'low', fn ($q) => $q->havingRaw('COALESCE(SUM(branch_product.stock), 0) <= products.minimum_stock'))
                    ->when(request('stock_status') === 'out', fn ($q) => $q->havingRaw('COALESCE(SUM(branch_product.stock), 0) = 0'))
                    ->select('products.*', DB::raw('COALESCE(SUM(branch_product.stock), 0) as stock'));
            }

            $products = $productsQuery->orderBy('name')->get();

            $pdf = Pdf::loadView('exports.inventory-pdf', [
                'products' => $products,
            ]);

            return $pdf->download('laporan-inventory.pdf');
        }

        return Excel::download($export, 'laporan-inventory.xlsx');
    }

    public function exportFinancial(string $format): BinaryFileResponse|\Illuminate\Http\Response
    {
        $dateFrom = request('date_from', now()->startOfMonth()->toDateString());
        $dateTo = request('date_to', now()->toDateString());
        $branchId = $this->getBranchId();

        $export = new FinancialExport($dateFrom, $dateTo, $branchId);

        if ($format === 'pdf') {
            $sales = Sale::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
                ->get();

            $omzet = $sales->sum('grand_total');

            $costOfGoodsSold = SaleItem::whereHas('sale', function ($q) use ($dateFrom, $dateTo, $branchId) {
                $q->whereDate('created_at', '>=', $dateFrom)
                    ->whereDate('created_at', '<=', $dateTo)
                    ->when($branchId, fn ($b) => $b->where('branch_id', $branchId));
            })->select(
                DB::raw('SUM(qty * (SELECT cost_price FROM products WHERE products.id = sale_items.product_id)) as total_cost')
            )->first()->total_cost ?? 0;

            $totalSalesValue = SaleItem::whereHas('sale', function ($q) use ($dateFrom, $dateTo, $branchId) {
                $q->whereDate('created_at', '>=', $dateFrom)
                    ->whereDate('created_at', '<=', $dateTo)
                    ->when($branchId, fn ($b) => $b->where('branch_id', $branchId));
            })->select(DB::raw('SUM(subtotal) as total'))->first()->total ?? 0;

            $grossProfit = $totalSalesValue - $costOfGoodsSold;
            $estimatedProfit = $grossProfit - $sales->sum('discount');

            $dailyTotals = Sale::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(grand_total) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $pdf = Pdf::loadView('exports.financial-pdf', [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'omzet' => $omzet,
                'grossProfit' => $grossProfit,
                'estimatedProfit' => $estimatedProfit,
                'costOfGoodsSold' => $costOfGoodsSold,
                'dailyTotals' => $dailyTotals,
                'totalSales' => $sales->count(),
                'totalSalesValue' => $totalSalesValue,
            ]);

            return $pdf->download('laporan-keuangan.pdf');
        }

        return Excel::download($export, 'laporan-keuangan.xlsx');
    }
}
