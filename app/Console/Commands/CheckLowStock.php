<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Setting;
use App\Notifications\LowStockNotification;
use Illuminate\Console\Command;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;

class CheckLowStock extends Command
{
    protected $signature = 'stock:check-low';

    protected $description = 'Check for low stock products and send WhatsApp notification';

    public function handle(): int
    {
        $enabled = Setting::get('whatsapp_notify_low_stock', false);
        if ($enabled !== '1') {
            $this->info('Low stock notification is disabled.');

            return Command::SUCCESS;
        }

        $phone = Setting::get('whatsapp_phone', '');
        if (empty($phone)) {
            $this->warn('WhatsApp phone number not configured.');

            return Command::FAILURE;
        }

        $products = Product::join('branch_product', 'products.id', '=', 'branch_product.product_id')
            ->join('branches', 'branches.id', '=', 'branch_product.branch_id')
            ->whereColumn('branch_product.stock', '<=', 'products.minimum_stock')
            ->where('products.status', true)
            ->select('products.*', 'branch_product.stock as branch_stock', 'branches.name as branch_name')
            ->orderByDesc('branch_product.stock')
            ->get();

        if ($products->isEmpty()) {
            $this->info('No low stock products found.');

            return Command::SUCCESS;
        }

        $notifiable = new class($phone) implements Notifiable
        {
            use Notifiable;

            public function __construct(public string $phone) {}

            public function routeNotificationForWhatsApp(): string
            {
                return $this->phone;
            }

            public function getKey(): mixed
            {
                return null;
            }
        };

        foreach ($products as $product) {
            Notification::send($notifiable, new LowStockNotification(
                product: $product,
                currentStock: (int) $product->branch_stock,
                branchName: $product->branch_name,
            ));
        }

        $this->info('Sent '.$products->count().' low stock notification(s).');

        return Command::SUCCESS;
    }
}
