<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MtnService
{
    protected $subscriptionKey;
    protected $apiUserId;
    protected $apiKey;
    protected $callbackUrl;
    protected $currency;
    protected $env;

    public function __construct()
    {
        $this->subscriptionKey = env('MOMO_SUB_KEY');
        $this->apiUserId = env('MOMO_USER_ID');
        $this->apiKey = env('MOMO_API_KEY');
        $this->callbackUrl = env('MOMO_CALLBACK_URL'); // must be publicly accessible
        $this->currency = env('MOMO_CURRENCY', 'UGX');
        $this->env = env('MOMO_ENV', 'sandbox'); // sandbox | production
    }

    /**
     * Get a valid access token
     */
    public function getAccessToken(): ?string
    {
        $url = $this->env === 'sandbox'
            ? "https://sandbox.momodeveloper.mtn.com/collection/token/"
            : "https://momodeveloper.mtn.com/collection/token/";

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->apiUserId . ':' . $this->apiKey),
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            'Accept' => 'application/json',
        ])->post($url);

        $body = $response->json();

        if (!isset($body['access_token'])) {
            Log::error('MTN getAccessToken failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;
        }

        return $body['access_token'];
    }

    /**
     * Request a payment from a user
     */
    public function requestPayment(string $phoneNumber, float $amount, string $externalId): string
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return 'error: unable to generate access token';
        }

        // Sandbox requires EUR
        $currency = $this->env === 'sandbox' ? 'EUR' : $this->currency;
        $amount = (int) round($amount); // must be integer

        $url = $this->env === 'sandbox'
            ? "https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay"
            : "https://momodeveloper.mtn.com/collection/v1_0/requesttopay";

        $referenceId = (string) Str::uuid();

        $payload = [
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => $externalId,
            'payer' => [
                'partyIdType' => 'MSISDN',
                'partyId' => $phoneNumber,
            ],
            'payerMessage' => "Payment for order {$externalId}",
            'payeeNote' => 'Thank you for your payment',
        ];

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'X-Reference-Id' => $referenceId,
            'X-Target-Environment' => $this->env,
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            'X-Callback-Url' => $this->callbackUrl,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($url, $payload);

        Log::info('MTN RequestTopUp Response', [
            'status' => $response->status(),
            'body' => $response->body(),
            'payload' => $payload
        ]);

        if ($response->status() === 202) {
            return $referenceId;
        }

        $body = $response->json();
        $message = $body['message'] ?? $response->body() ?? 'Unknown error';
        return "error: {$message}";
    }

    /**
     * Poll payment status
     */
    public function getPaymentStatus(string $referenceId): array
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return ['status' => 'error', 'message' => 'Unable to generate access token'];
        }

        $url = $this->env === 'sandbox'
            ? "https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay/{$referenceId}"
            : "https://momodeveloper.mtn.com/collection/v1_0/requesttopay/{$referenceId}";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'X-Target-Environment' => $this->env,
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            'Accept' => 'application/json',
        ])->get($url);

        if ($response->failed()) {
            Log::error('MTN getPaymentStatus failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        }

        return $response->json();
    }
}
