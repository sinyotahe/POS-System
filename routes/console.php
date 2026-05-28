<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('backup:database')
    ->dailyAt('00:00')
    ->description('Backup database setiap hari tengah malam.');

Schedule::command('report:daily')
    ->dailyAt('20:00')
    ->description('Kirim laporan penjualan harian via WhatsApp.');

Schedule::command('stock:check-low')
    ->everySixHours()
    ->description('Kirim notifikasi stok minimum via WhatsApp.');
