<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Services\ThermalPrintService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class PrintController extends Controller
{
    public function thermal(Sale $sale): Response|RedirectResponse
    {
        if (auth()->user()->role === 'kasir' && $sale->cashier_id !== auth()->id()) {
            abort(403);
        }

        $sale->load(['items.product', 'cashier']);

        $service = new ThermalPrintService;
        $data = $service->generateReceipt($sale);

        if (empty($data)) {
            return redirect()->route('sales.show', $sale)
                ->with('error', 'Printer thermal tidak terhubung.');
        }

        return response($data, 200, [
            'Content-Type' => $service->getSupportedMimeType(),
            'Content-Disposition' => 'inline; filename="receipt-'.$sale->invoice_number.'.bin"',
        ]);
    }
}
