<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $today = today();
        $branchId = $this->getBranchId();
        $userId = auth()->id();
        $isKasir = auth()->user()->role === 'kasir';

        $todaySales = Sale::whereDate('created_at', $today)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when($isKasir, fn ($q) => $q->where('cashier_id', $userId));
        $todaySalesTotal = (float) $todaySales->sum('grand_total');
        $todayTransactionsCount = $todaySales->count();

        $topProductsToday = SaleItem::select(
            'product_id',
            DB::raw('SUM(qty) as total_qty')
        )
            ->whereHas('sale', function ($q) use ($today, $branchId, $isKasir, $userId) {
                $q->whereDate('created_at', $today)
                    ->when($branchId, fn ($b) => $b->where('branch_id', $branchId))
                    ->when($isKasir, fn ($b) => $b->where('cashier_id', $userId));
            })
            ->with('product:id,name,image')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $topProductsMonth = SaleItem::select(
            'product_id',
            DB::raw('SUM(qty) as total_qty')
        )
            ->whereHas('sale', function ($q) use ($branchId, $isKasir, $userId) {
                $q->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->when($branchId, fn ($b) => $b->where('branch_id', $branchId))
                    ->when($isKasir, fn ($b) => $b->where('cashier_id', $userId));
            })
            ->with('product:id,name,image')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $lowStockProducts = Product::with('category')
            ->where('status', true)
            ->when($branchId, function ($q) use ($branchId) {
                $q->join('branch_product', function ($join) use ($branchId) {
                    $join->on('products.id', '=', 'branch_product.product_id')
                        ->where('branch_product.branch_id', $branchId);
                })
                    ->whereColumn('branch_product.stock', '<=', 'products.minimum_stock')
                    ->orderBy('branch_product.stock')
                    ->select('products.*', 'branch_product.stock as stock');
            }, function ($q) {
                $q->join('branch_product', 'products.id', '=', 'branch_product.product_id')
                    ->groupBy('products.id')
                    ->havingRaw('COALESCE(SUM(branch_product.stock), 0) <= products.minimum_stock')
                    ->orderByRaw('COALESCE(SUM(branch_product.stock), 0)')
                    ->select('products.*', DB::raw('COALESCE(SUM(branch_product.stock), 0) as stock'));
            })
            ->limit(10)
            ->get();

        $thisMonth = Sale::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when($isKasir, fn ($q) => $q->where('cashier_id', $userId));
        $thisMonthOmzet = (float) $thisMonth->sum('grand_total');
        $thisMonthTransactions = $thisMonth->count();

        $lastMonth = Sale::whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when($isKasir, fn ($q) => $q->where('cashier_id', $userId));
        $lastMonthOmzet = (float) $lastMonth->sum('grand_total');

        $growthPercent = $lastMonthOmzet > 0
            ? round(($thisMonthOmzet - $lastMonthOmzet) / $lastMonthOmzet * 100, 1)
            : ($thisMonthOmzet > 0 ? 100 : 0);

        $monthSalesValue = SaleItem::whereHas('sale', function ($q) use ($branchId, $isKasir, $userId) {
            $q->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->when($branchId, fn ($b) => $b->where('branch_id', $branchId))
                ->when($isKasir, fn ($b) => $b->where('cashier_id', $userId));
        })->select(DB::raw('SUM(subtotal) as total'))->first()->total ?? 0;

        $monthCostOfGoodsSold = SaleItem::whereHas('sale', function ($q) use ($branchId, $isKasir, $userId) {
            $q->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->when($branchId, fn ($b) => $b->where('branch_id', $branchId))
                ->when($isKasir, fn ($b) => $b->where('cashier_id', $userId));
        })->select(
            DB::raw('SUM(qty * (SELECT cost_price FROM products WHERE products.id = sale_items.product_id)) as total_cost')
        )->first()->total_cost ?? 0;

        $monthGrossProfit = $monthSalesValue - $monthCostOfGoodsSold;

        $last7Days = collect(range(6, 0))->map(function ($day) {
            return today()->subDays($day);
        });

        $salesChart = $last7Days->map(function ($date) use ($branchId, $isKasir, $userId) {
            $total = Sale::whereDate('created_at', $date)
                ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
                ->when($isKasir, fn ($q) => $q->where('cashier_id', $userId))
                ->sum('grand_total');

            $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

            return [
                'date' => $date->format('Y-m-d'),
                'label' => $dayNames[(int) $date->format('w')],
                'total' => (float) $total,
            ];
        });

        $transactionsChart = $last7Days->map(function ($date) use ($branchId, $isKasir, $userId) {
            $count = Sale::whereDate('created_at', $date)
                ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
                ->when($isKasir, fn ($q) => $q->where('cashier_id', $userId))
                ->count();

            $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

            return [
                'date' => $date->format('Y-m-d'),
                'label' => $dayNames[(int) $date->format('w')],
                'count' => $count,
            ];
        });

        return Inertia::render('Dashboard', [
            'today_sales_total' => $todaySalesTotal,
            'today_transactions_count' => $todayTransactionsCount,
            'low_stock_count' => $lowStockProducts->count(),
            'top_products' => $topProductsToday,
            'top_products_month' => $topProductsMonth,
            'low_stock_products' => $lowStockProducts,
            'sales_chart' => $salesChart,
            'this_month_omzet' => $thisMonthOmzet,
            'this_month_transactions' => $thisMonthTransactions,
            'last_month_omzet' => $lastMonthOmzet,
            'growth_percent' => $growthPercent,
            'month_gross_profit' => $monthGrossProfit,
            'transactions_chart' => $transactionsChart,
            'userRole' => auth()->user()->role,
        ]);
    }
}
