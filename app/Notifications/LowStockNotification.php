<?php

namespace App\Notifications;

use App\Channels\WhatsAppChannel;
use App\Channels\WhatsAppMessage;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Product $product,
        public int $currentStock,
        public string $branchName = '',
    ) {}

    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        $product = $this->product;
        $location = $this->branchName ? " di {$this->branchName}" : '';

        $content = "⚠️ *Peringatan Stok Minimum*{$location}\n\n"
            ."Produk: {$product->name}\n"
            ."SKU: {$product->sku}\n"
            ."Stok saat ini: {$this->currentStock}\n"
            ."Stok minimum: {$product->minimum_stock}\n\n"
            .'Segera lakukan restok!';

        return new WhatsAppMessage(
            to: $notifiable->routeNotificationForWhatsApp(),
            content: $content,
        );
    }
}
