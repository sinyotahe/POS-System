<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

abstract class Controller
{
    protected function getBranchId(): ?int
    {
        $user = auth()->user();

        if (! $user) {
            return null;
        }

        if (in_array($user->role, ['admin', 'owner'])) {
            return Session::get('active_branch_id', $user->branch_id);
        }

        return $user->branch_id;
    }

    protected function getBranch(): ?Branch
    {
        $branchId = $this->getBranchId();

        return $branchId ? Branch::find($branchId) : null;
    }

    protected function getProductStock(Product $product, ?int $branchId = null): int
    {
        $branchId ??= $this->getBranchId();

        if ($branchId) {
            $bp = $product->branches()->where('branch_id', $branchId)->first();

            return $bp ? (int) $bp->pivot->stock : 0;
        }

        return 0;
    }

    protected function incrementProductStock(Product $product, int $qty, ?int $branchId = null): void
    {
        $branchId ??= $this->getBranchId();

        if (! $branchId) {
            return;
        }

        DB::table('branch_product')
            ->updateOrInsert(
                ['branch_id' => $branchId, 'product_id' => $product->id],
                ['stock' => DB::raw('COALESCE(stock, 0) + '.$qty), 'created_at' => now(), 'updated_at' => now()]
            );
    }

    protected function decrementProductStock(Product $product, int $qty, ?int $branchId = null): bool
    {
        $branchId ??= $this->getBranchId();

        if (! $branchId) {
            return false;
        }

        $affected = DB::table('branch_product')
            ->where('branch_id', $branchId)
            ->where('product_id', $product->id)
            ->where('stock', '>=', $qty)
            ->decrement('stock', $qty);

        return $affected > 0;
    }
}
