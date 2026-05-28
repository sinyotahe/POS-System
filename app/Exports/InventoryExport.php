<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $search = null,
        protected ?string $stockStatus = null,
        protected ?int $branchId = null,
    ) {}

    public function query(): Builder|\Illuminate\Database\Query\Builder|Relation
    {
        $query = Product::with('category');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('sku', 'like', "%{$this->search}%");
            });
        }

        if ($this->branchId) {
            $query
                ->join('branch_product', fn ($j) => $j->on('products.id', '=', 'branch_product.product_id')->where('branch_product.branch_id', $this->branchId))
                ->select('products.*', 'branch_product.stock as stock');

            if ($this->stockStatus === 'low') {
                $query->whereColumn('branch_product.stock', '<=', 'products.minimum_stock');
            } elseif ($this->stockStatus === 'out') {
                $query->where('branch_product.stock', 0);
            }
        } else {
            $query
                ->join('branch_product', 'products.id', '=', 'branch_product.product_id')
                ->groupBy('products.id')
                ->select('products.*', DB::raw('COALESCE(SUM(branch_product.stock), 0) as stock'));

            if ($this->stockStatus === 'low') {
                $query->havingRaw('COALESCE(SUM(branch_product.stock), 0) <= products.minimum_stock');
            } elseif ($this->stockStatus === 'out') {
                $query->havingRaw('COALESCE(SUM(branch_product.stock), 0) = 0');
            }
        }

        return $query->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Nama',
            'Kategori',
            'Harga Beli',
            'Harga Jual',
            'Stok',
            'Stok Minimum',
            'Status',
            'Nilai Stok',
        ];
    }

    public function map($product): array
    {
        return [
            $product->sku,
            $product->name,
            $product->category?->name ?? '-',
            (float) $product->cost_price,
            (float) $product->sell_price,
            (int) $product->stock,
            (int) $product->minimum_stock,
            $product->stock <= $product->minimum_stock ? 'Minimum' : 'Aman',
            (float) ($product->stock * $product->cost_price),
        ];
    }
}
