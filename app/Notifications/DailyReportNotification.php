<?php

namespace App\Notifications;

use App\Channels\WhatsAppChannel;
use App\Channels\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DailyReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $data,
    ) {}

    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        $d = $this->data;

        $content = "📊 *Laporan Penjualan Harian*\n"
            ."Periode: {$d['date']}\n\n"
            .'💵 Omzet: Rp '.number_format($d['omzet'], 0, ',', '.')."\n"
            ."🛒 Transaksi: {$d['transactions']}\n"
            .'💰 Laba Kotor: Rp '.number_format($d['gross_profit'], 0, ',', '.')."\n"
            ."📈 Margin: {$d['profit_margin']}%\n\n";

        if (! empty($d['low_stock_count'])) {
            $content .= "⚠️ *{$d['low_stock_count']}* produk perlu restok!\n\n";
        }

        $content .= 'Terima kasih.';

        return new WhatsAppMessage(
            to: $notifiable->routeNotificationForWhatsApp(),
            content: $content,
        );
    }
}
