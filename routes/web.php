<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin,owner')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::get('products/barcode-print', [ProductController::class, 'barcodePrint'])->name('products.barcode-print')->middleware('role:admin');
        Route::resource('suppliers', SupplierController::class)->except(['show']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('branches', BranchController::class);
        Route::resource('users', UserController::class);
        Route::resource('stock-ins', StockInController::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('stock-outs', StockOutController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('/stock-adjustments/create', [StockAdjustmentController::class, 'create'])->name('stock-adjustments.create');
        Route::post('/stock-adjustments', [StockAdjustmentController::class, 'store'])->name('stock-adjustments.store');
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        Route::get('/stock-transfers', [StockTransferController::class, 'index'])->name('stock-transfers.index');
        Route::get('/stock-transfers/create', [StockTransferController::class, 'create'])->name('stock-transfers.create');
        Route::post('/stock-transfers', [StockTransferController::class, 'store'])->name('stock-transfers.store');
    });

    Route::post('/branch/switch', [BranchController::class, 'switchBranch'])->name('branch.switch')->middleware('role:admin,owner');

    Route::middleware('role:admin,owner')->group(function () {
        Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');

        Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
        Route::get('/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
        Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
        Route::get('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
        Route::post('/purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
        Route::post('/purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
        Route::post('/purchase-orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');
        Route::get('/purchase-orders/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
        Route::put('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
        Route::delete('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');
    });

    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::get('/pos/search-products', [PosController::class, 'searchProducts'])->name('pos.search-products')->middleware('throttle:60,1');
        Route::post('/pos', [PosController::class, 'store'])->name('pos.store')->middleware('throttle:30,1');
        Route::get('/pos/cart', [PosController::class, 'getCart'])->name('pos.cart');
        Route::post('/pos/hold', [PosController::class, 'hold'])->name('pos.hold')->middleware('throttle:10,1');
        Route::get('/pos/held-carts', [PosController::class, 'heldCarts'])->name('pos.held-carts');
        Route::delete('/pos/hold/{heldCart}', [PosController::class, 'unhold'])->name('pos.unhold');
    });

    Route::middleware('role:admin,kasir,owner')->group(function () {
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
        Route::get('/sales/{sale}/print-thermal', [PrintController::class, 'thermal'])->name('sales.print-thermal');
        Route::post('/sales/{sale}/void', [SaleController::class, 'void'])->name('sales.void')->middleware('role:admin');
    });

    Route::middleware('role:admin,owner,kasir')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory')->middleware('role:admin,owner');
        Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial')->middleware('role:admin,owner');
        Route::get('/reports/branch-comparison', [ReportController::class, 'branchComparison'])->name('reports.branch-comparison')->middleware('role:admin,owner');

        Route::get('/reports/sales/export/{format}', [ReportController::class, 'exportSales'])->name('reports.sales.export')->middleware('role:admin,owner');
        Route::get('/reports/inventory/export/{format}', [ReportController::class, 'exportInventory'])->name('reports.inventory.export')->middleware('role:admin,owner');
        Route::get('/reports/financial/export/{format}', [ReportController::class, 'exportFinancial'])->name('reports.financial.export')->middleware('role:admin,owner');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
