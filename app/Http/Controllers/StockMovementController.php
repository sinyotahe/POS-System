<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Inertia\Inertia;
use Inertia\Response;

class StockMovementController extends Controller
{
    public function index(): Response
    {
        $branchId = $this->getBranchId();

        $movements = StockMovement::query()
            ->with(['product', 'user', 'branch'])
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when(request('product_id'), function ($query, $productId) {
                $query->where('product_id', $productId);
            })
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
            ->paginate(20);

        return Inertia::render('StockMovements/Index', [
            'movements' => $movements,
            'products' => Product::orderBy('name')->get(['id', 'name', 'sku']),
            'filters' => request()->only('product_id', 'type', 'date_from', 'date_to'),
        ]);
    }
}
