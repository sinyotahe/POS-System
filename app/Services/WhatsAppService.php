<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiKey;

    protected string $apiToken;

    protected string $provider;

    public function __construct()
    {
        $this->provider = config('whatsapp.provider', 'fonnte');
        $this->apiKey = Setting::get('whatsapp_api_key') ?: config('whatsapp.api_key', '');
        $this->apiToken = config('whatsapp.api_token', '');
    }

    public function sendText(string $phone, string $message): bool
    {
        if (empty($this->apiKey) && empty($this->apiToken)) {
            Log::warning('WhatsApp API key/token not configured');

            return false;
        }

        try {
            $response = Http::post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ], [
                'Authorization' => $this->apiToken ?: $this->apiKey,
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['status'] ?? false)) {
                return true;
            }

            Log::warning('WhatsApp send failed', ['response' => $result]);

            return false;
        } catch (\Throwable $e) {
            Log::error('WhatsApp exception', ['error' => $e->getMessage()]);

            return false;
        }
    }

    public function sendDocument(string $phone, string $filePath, string $filename): bool
    {
        if (empty($this->apiKey) && empty($this->apiToken)) {
            Log::warning('WhatsApp API key/token not configured');

            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiToken ?: $this->apiKey,
            ])->attach(
                'file', file_get_contents($filePath), $filename
            )->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'countryCode' => '62',
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['status'] ?? false)) {
                return true;
            }

            Log::warning('WhatsApp document send failed', ['response' => $result]);

            return false;
        } catch (\Throwable $e) {
            Log::error('WhatsApp document exception', ['error' => $e->getMessage()]);

            return false;
        }
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey) || ! empty($this->apiToken);
    }
}
