<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f5f5f5; font-weight: bold; }
        .text-right { text-align: right; }
        .mb-4 { margin-bottom: 16px; }
        .font-bold { font-weight: bold; }
        h2 { margin-bottom: 4px; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    <p class="mb-4">Periode: {{ $dateFrom ?? 'Semua' }} s/d {{ $dateTo ?? 'Semua' }}</p>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th class="text-right">Total</th>
                <th class="text-right">Diskon</th>
                <th class="text-right">Pajak</th>
                <th class="text-right">Grand Total</th>
                <th>Metode Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ $sale->created_at }}</td>
                <td>{{ $sale->cashier?->name ?? '-' }}</td>
                <td>{{ $sale->customer_name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($sale->tax, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                <td>{{ $sale->payment_method }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td colspan="4" class="text-right">Total</td>
                <td class="text-right">Rp {{ number_format($sales->sum('total'), 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($sales->sum('discount'), 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($sales->sum('tax'), 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($sales->sum('grand_total'), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
