<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventory</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f5f5f5; font-weight: bold; }
        .text-right { text-align: right; }
        .mb-4 { margin-bottom: 16px; }
        .font-bold { font-weight: bold; }
        h2 { margin-bottom: 4px; }
        .text-red { color: #dc2626; }
    </style>
</head>
<body>
    <h2>Laporan Inventory</h2>
    <p class="mb-4">Per {{ date('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th class="text-right">Harga Beli</th>
                <th class="text-right">Harga Jual</th>
                <th class="text-right">Stok</th>
                <th class="text-right">Stok Minimum</th>
                <th>Status</th>
                <th class="text-right">Nilai Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category?->name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                <td class="text-right @if($product->stock <= $product->minimum_stock) text-red @endif">{{ $product->stock }}</td>
                <td class="text-right">{{ $product->minimum_stock }}</td>
                <td>{{ $product->stock <= $product->minimum_stock ? 'Minimum' : 'Aman' }}</td>
                <td class="text-right">Rp {{ number_format($product->stock * $product->cost_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
