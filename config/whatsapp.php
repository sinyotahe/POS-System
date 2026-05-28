<?php

return [
    'provider' => env('WHATSAPP_PROVIDER', 'fonnte'),
    'api_key' => env('WHATSAPP_API_KEY', ''),
    'api_token' => env('WHATSAPP_API_TOKEN', ''),
    'phone_number' => env('WHATSAPP_PHONE_NUMBER', ''),
    'notifications' => [
        'low_stock' => env('WHATSAPP_NOTIFY_LOW_STOCK', false),
        'daily_report' => env('WHATSAPP_NOTIFY_DAILY_REPORT', false),
        'sale_receipt' => env('WHATSAPP_NOTIFY_SALE_RECEIPT', false),
    ],
];
