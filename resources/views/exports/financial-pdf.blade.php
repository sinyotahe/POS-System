<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f5f5f5; font-weight: bold; }
        .text-right { text-align: right; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }
        .font-bold { font-weight: bold; }
        h2 { margin-bottom: 4px; }
        .summary { font-size: 14px; }
        .summary td { padding: 4px 6px; }
    </style>
</head>
<body>
    <h2>Laporan Keuangan</h2>
    <p class="mb-4">Periode: {{ $dateFrom }} s/d {{ $dateTo }}</p>

    <table class="summary mb-4">
        <tr>
            <td class="font-bold">Omzet</td>
            <td class="text-right">Rp {{ number_format($omzet, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="font-bold">Total Penjualan</td>
            <td class="text-right">Rp {{ number_format($totalSalesValue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="font-bold">HPP</td>
            <td class="text-right">Rp {{ number_format($costOfGoodsSold, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="font-bold">Laba Kotor</td>
            <td class="text-right">Rp {{ number_format($grossProfit, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="font-bold">Laba Bersih (Estimasi)</td>
            <td class="text-right">Rp {{ number_format($estimatedProfit, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="font-bold">Total Transaksi</td>
            <td class="text-right">{{ $totalSales }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-right">Total Penjualan</th>
                <th class="text-right">Jumlah Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dailyTotals as $day)
            <tr>
                <td>{{ $day->date }}</td>
                <td class="text-right">Rp {{ number_format($day->total, 0, ',', '.') }}</td>
                <td class="text-right">{{ $day->count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
