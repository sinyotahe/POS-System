<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $dateFrom = null,
        protected ?string $dateTo = null,
        protected ?int $branchId = null,
    ) {}

    public function query(): Builder|\Illuminate\Database\Query\Builder|Relation
    {
        $query = Sale::query()
            ->whereDate('created_at', '>=', $this->dateFrom ?? now()->startOfMonth())
            ->whereDate('created_at', '<=', $this->dateTo ?? now());

        if ($this->branchId) {
            $query->where('branch_id', $this->branchId);
        }

        return $query->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Invoice',
            'Pelanggan',
            'Total Penjualan',
            'Diskon',
            'Pajak',
            'Grand Total',
            'Metode Bayar',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->created_at,
            $sale->invoice_number,
            $sale->customer_name ?? '-',
            (float) $sale->total,
            (float) $sale->discount,
            (float) $sale->tax,
            (float) $sale->grand_total,
            $sale->payment_method,
        ];
    }
}
