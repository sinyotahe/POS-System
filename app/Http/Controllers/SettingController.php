<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Settings/Index', [
            'tax_rate' => (float) (Setting::get('tax_rate') ?? config('app.tax_rate')),
            'whatsapp_api_key' => Setting::get('whatsapp_api_key', ''),
            'whatsapp_phone' => Setting::get('whatsapp_phone', ''),
            'whatsapp_notify_low_stock' => Setting::get('whatsapp_notify_low_stock', false) === '1',
            'whatsapp_notify_daily_report' => Setting::get('whatsapp_notify_daily_report', false) === '1',
            'whatsapp_notify_sale_receipt' => Setting::get('whatsapp_notify_sale_receipt', false) === '1',
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tax_rate' => 'required|numeric|min:0|max:100',
            'whatsapp_api_key' => 'nullable|string',
            'whatsapp_phone' => 'nullable|string',
            'whatsapp_notify_low_stock' => 'boolean',
            'whatsapp_notify_daily_report' => 'boolean',
            'whatsapp_notify_sale_receipt' => 'boolean',
        ]);

        Setting::set('tax_rate', (string) ($validated['tax_rate'] / 100));
        Setting::set('whatsapp_api_key', $validated['whatsapp_api_key'] ?? '');
        Setting::set('whatsapp_phone', $validated['whatsapp_phone'] ?? '');
        Setting::set('whatsapp_notify_low_stock', $request->boolean('whatsapp_notify_low_stock') ? '1' : '0');
        Setting::set('whatsapp_notify_daily_report', $request->boolean('whatsapp_notify_daily_report') ? '1' : '0');
        Setting::set('whatsapp_notify_sale_receipt', $request->boolean('whatsapp_notify_sale_receipt') ? '1' : '0');

        ActivityLog::log('update', 'setting', null, 'Pengaturan diubah');

        return redirect()->route('settings.edit')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
