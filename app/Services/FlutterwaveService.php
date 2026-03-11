<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    protected $secretKey;
    protected $publicKey;
    protected $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->publicKey = config('services.flutterwave.public_key');
    }

    public function initializePayment(array $data)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/payments", [
                    'tx_ref'          => $data['tx_ref'],
                    'amount'          => $data['amount'],
                    'currency'        => $data['currency'] ?? 'UGX',
                    'redirect_url'    => $data['redirect_url'],
                    'customer'        => [
                        'email'        => $data['email'],
                        'phonenumber'  => $data['phone'] ?? '',
                        'name'         => $data['name'],
                    ],
                    'customizations' => [
                        'title'       => 'Ticket Purchase',
                        'description' => $data['description'] ?? 'Event Ticket Payment',
                    ],
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Flutterwave Initialization Failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return ['status' => 'error', 'message' => 'Failed to initialize payment'];

        } catch (\Exception $e) {
            Log::error('Flutterwave Service Error', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function verifyTransaction($transactionId)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Flutterwave Verification Failed', [
                'id'     => $transactionId,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Flutterwave Verification Error', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
