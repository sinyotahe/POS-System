<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StockAuditCommand extends Command
{
    protected $signature = 'stock:audit {--fix : Fix any discrepancies found}';

    protected $description = 'Audit and optionally repair stock discrepancies across branches';

    public function handle(): int
    {
        $products = Product::with('branches')->cursor();
        $issues = 0;

        $this->info('Audit stok dimulai...');
        $this->newLine();

        $bar = $this->output->createProgressBar($products->count());

        foreach ($products as $product) {
            $totalBranchStock = (int) $product->branches->sum('pivot.stock');

            if ($totalBranchStock < 0) {
                $this->newLine();
                $this->warn("Produk {$product->sku} ({$product->name}): Stok cabang negatif ({$totalBranchStock})");

                if ($this->option('fix')) {
                    foreach ($product->branches as $branch) {
                        if ($branch->pivot->stock < 0) {
                            DB::table('branch_product')
                                ->where('branch_id', $branch->id)
                                ->where('product_id', $product->id)
                                ->update(['stock' => 0]);
                            $this->info("  -> Cabang {$branch->name}: stok direset ke 0");
                        }
                    }
                }
                $issues++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($issues === 0) {
            $this->info('✅ Tidak ada masalah stok ditemukan.');
        } else {
            $this->warn("⚠️  Ditemukan {$issues} produk dengan masalah stok.");
        }

        return Command::SUCCESS;
    }
}
