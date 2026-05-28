<?php

namespace App\Notifications;

use App\Channels\WhatsAppChannel;
use App\Channels\WhatsAppMessage;
use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SaleReceiptNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Sale $sale) {}

    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        $sale = $this->sale;
        $items = $sale->items->map(fn ($i) => "{$i->product?->name} x{$i->qty} = Rp ".number_format($i->subtotal, 0, ',', '.'))
            ->implode("\n");

        $paymentLabels = [
            'cash' => 'Tunai',
            'transfer' => 'Transfer',
            'qris' => 'QRIS',
            'e-wallet' => 'E-Wallet',
        ];

        $content = "🧾 *Struk Pembelian*\n\n"
            ."Invoice: {$sale->invoice_number}\n"
            ."Tanggal: {$sale->created_at}\n"
            ."Kasir: {$sale->cashier?->name}\n"
            .($sale->customer_name ? "Pelanggan: {$sale->customer_name}\n" : '')
            ."\n*Items:*\n{$items}\n\n"
            .'Total: Rp '.number_format($sale->total, 0, ',', '.')."\n"
            .($sale->discount > 0 ? 'Diskon: -Rp '.number_format($sale->discount, 0, ',', '.')."\n" : '')
            .'Pajak: Rp '.number_format($sale->tax, 0, ',', '.')."\n"
 .'*Grand Total: Rp '.number_format($sale->grand_total, 0, ',', '.')."*\n\n"
            .'Pembayaran: '.($paymentLabels[$sale->payment_method] ?? $sale->payment_method)."\n"
            .'Dibayar: Rp '.number_format($sale->paid_amount, 0, ',', '.')."\n"
            .'Kembali: Rp '.number_format($sale->change_amount, 0, ',', '.')."\n\n"
            .'Terima kasih!';

        return new WhatsAppMessage(
            to: $notifiable->routeNotificationForWhatsApp(),
            content: $content,
        );
    }
}
