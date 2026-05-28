<?php

namespace App\Services;

use App\Models\Sale;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class ThermalPrintService
{
    public function generateReceipt(Sale $sale): string
    {
        $sale->load(['items.product', 'cashier', 'branch']);

        try {
            $connector = $this->getConnector();
            $printer = new Printer($connector);
        } catch (\Throwable $e) {
            return '';
        }

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->text("POS System\n");
        $printer->selectPrintMode();
        $printer->text("Struk Penjualan\n");
        $printer->feed(2);

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Cabang: {$sale->branch?->name}\n");
        $printer->text("Invoice: {$sale->invoice_number}\n");
        $printer->text("Tanggal: {$sale->created_at}\n");
        $printer->text("Kasir: {$sale->cashier?->name}\n");
        if ($sale->customer_name) {
            $printer->text("Pelanggan: {$sale->customer_name}\n");
        }
        $printer->feed(1);

        $printer->text(str_repeat('=', 32)."\n");

        foreach ($sale->items as $item) {
            $name = mb_substr($item->product?->name ?? 'Unknown', 0, 20);
            $line = sprintf(
                "%-20s %3d x %8s\n%30s\n",
                $name,
                $item->qty,
                number_format($item->price, 0, ',', '.'),
                'Rp '.number_format($item->subtotal, 0, ',', '.')
            );
            $printer->text($line);
        }

        $printer->text(str_repeat('=', 32)."\n");
        $printer->feed(1);

        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text(sprintf("%-20s: %s\n", 'Subtotal', 'Rp '.number_format($sale->total, 0, ',', '.')));
        if ($sale->discount > 0) {
            $printer->text(sprintf("%-20s: -%s\n", 'Diskon', 'Rp '.number_format($sale->discount, 0, ',', '.')));
        }
        $taxPercent = ($sale->tax_rate ?? 0) * 100;
        $printer->text(sprintf("%-20s: %s\n", 'Pajak ('.((int) $taxPercent).'%)', 'Rp '.number_format($sale->tax, 0, ',', '.')));
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->text(sprintf("%-20s: %s\n", 'Grand Total', 'Rp '.number_format($sale->grand_total, 0, ',', '.')));
        $printer->selectPrintMode();
        $printer->feed(1);

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text(sprintf("%-20s: %s\n", 'Pembayaran', $sale->payment_method));
        $printer->text(sprintf("%-20s: %s\n", 'Dibayar', 'Rp '.number_format($sale->paid_amount, 0, ',', '.')));
        $printer->text(sprintf("%-20s: %s\n", 'Kembali', 'Rp '.number_format($sale->change_amount, 0, ',', '.')));
        $printer->feed(2);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Terima kasih atas kunjungan Anda\n");
        $printer->feed(3);

        try {
            $printer->cut();
            $data = $printer->getData();
            $printer->close();

            return $data;
        } catch (\Throwable $e) {
            return '';
        }
    }

    protected function getConnector(): mixed
    {
        $type = config('thermal-printer.connection', 'file');
        $path = config('thermal-printer.path', '/dev/usb/lp0');
        $ip = config('thermal-printer.ip', '192.168.1.100');
        $port = config('thermal-printer.port', 9100);

        return match ($type) {
            'network' => new NetworkPrintConnector($ip, $port),
            default => new FilePrintConnector($path),
        };
    }

    public function getSupportedMimeType(): string
    {
        return 'application/octet-stream';
    }
}
