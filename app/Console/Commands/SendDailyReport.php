<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Setting;
use App\Notifications\DailyReportNotification;
use Illuminate\Console\Command;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SendDailyReport extends Command
{
    protected $signature = 'report:daily';

    protected $description = 'Send daily sales report to owner via WhatsApp';

    public function handle(): int
    {
        $enabled = Setting::get('whatsapp_notify_daily_report', false);
        if ($enabled !== '1') {
            $this->info('Daily report notification is disabled.');

            return Command::SUCCESS;
        }

        $phone = Setting::get('whatsapp_phone', '');
        if (empty($phone)) {
            $this->warn('WhatsApp phone number not configured.');

            return Command::FAILURE;
        }

        $today = today();
        $branchId = null;

        $totalOmzet = (float) Sale::whereDate('created_at', $today)
            ->sum('grand_total');
        $totalTransactions = Sale::whereDate('created_at', $today)->count();

        $totalSalesValue = SaleItem::whereHas('sale', fn ($q) => $q->whereDate('created_at', $today))
            ->select(DB::raw('SUM(subtotal) as total'))->first()->total ?? 0;

        $totalCost = SaleItem::whereHas('sale', fn ($q) => $q->whereDate('created_at', $today))
            ->select(DB::raw('SUM(qty * (SELECT cost_price FROM products WHERE products.id = sale_items.product_id)) as total_cost'))
            ->first()->total_cost ?? 0;

        $grossProfit = $totalSalesValue - $totalCost;
        $profitMargin = $totalOmzet > 0 ? round(($grossProfit / $totalOmzet) * 100, 1) : 0;

        $lowStockCount = Product::join('branch_product', 'products.id', '=', 'branch_product.product_id')
            ->groupBy('products.id')
            ->havingRaw('COALESCE(SUM(branch_product.stock), 0) <= products.minimum_stock')
            ->count();

        $data = [
            'date' => $today->format('d/m/Y'),
            'omzet' => $totalOmzet,
            'transactions' => $totalTransactions,
            'gross_profit' => $grossProfit,
            'profit_margin' => $profitMargin,
            'low_stock_count' => $lowStockCount,
        ];

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

        Notification::send($notifiable, new DailyReportNotification($data));

        $this->info('Daily report sent.');

        return Command::SUCCESS;
    }
}
