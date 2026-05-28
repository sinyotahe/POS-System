<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $dateFrom = null,
        protected ?string $dateTo = null,
        protected ?string $period = null,
        protected ?int $cashierId = null,
        protected ?int $branchId = null,
    ) {}

    public function query(): Builder|\Illuminate\Database\Query\Builder|Relation
    {
        $query = Sale::query()->with('cashier');

        if ($this->period === 'daily') {
            $query->whereDate('created_at', today());
        } elseif ($this->period === 'weekly') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->period === 'monthly') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if ($this->cashierId) {
            $query->where('cashier_id', $this->cashierId);
        }

        if ($this->branchId) {
            $query->where('branch_id', $this->branchId);
        }

        return $query->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Tanggal',
            'Kasir',
            'Pelanggan',
            'Total',
            'Diskon',
            'Pajak',
            'Grand Total',
            'Metode Bayar',
            'Dibayar',
            'Kembali',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->invoice_number,
            $sale->created_at,
            $sale->cashier?->name ?? '-',
            $sale->customer_name ?? '-',
            (float) $sale->total,
            (float) $sale->discount,
            (float) $sale->tax,
            (float) $sale->grand_total,
            $sale->payment_method,
            (float) $sale->paid_amount,
            (float) $sale->change_amount,
        ];
    }
}
