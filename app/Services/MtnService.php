<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MtnService
{
    protected string $subscriptionKey;
    protected string $apiUserId;
    protected string $apiKey;
    protected string $callbackUrl;
    protected string $currency;
    protected string $env;

    public function __construct()
    {
        $this->subscriptionKey = env('MOMO_SUB_KEY');
        $this->apiUserId = env('MOMO_USER_ID');
        $this->apiKey = env('MOMO_API_KEY');
        $this->callbackUrl = env('MOMO_CALLBACK_URL');
        $this->currency = env('MOMO_CURRENCY', 'UGX');
        $this->env = env('MOMO_ENV', 'sandbox'); // sandbox | production
    }

    /**
     * Base API URL
     */
    protected function baseUrl(): string
    {
        return $this->env === 'sandbox'
            ? 'https://sandbox.momodeveloper.mtn.com'
            : 'https://proxy.momoapi.mtn.com';
    }

    /**
     * MTN Target Environment
     */
    protected function targetEnvironment(): string
    {
        return $this->env === 'sandbox'
            ? 'sandbox'
            : 'mtnuganda';
    }

    /**
     * Format phone number to MTN format
     * Example:
     * 0771234567 => 256771234567
     */
    protected function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (Str::startsWith($phone, '0')) {
            $phone = '256' . substr($phone, 1);
        }

        if (!Str::startsWith($phone, '256')) {
            $phone = '256' . $phone;
        }

        return $phone;
    }

    /**
     * Get MTN access token
     */
    public function getAccessToken(): ?string
    {
        return Cache::remember(
            'mtn_access_token',
            now()->addMinutes(50),
            function () {

                $url = $this->baseUrl() . '/collection/token/';

                $response = Http::retry(3, 1000)
                    ->timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Basic ' . base64_encode(
                            $this->apiUserId . ':' . $this->apiKey
                        ),
                        'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                        'Accept' => 'application/json',
                    ])->post($url);

                $body = $response->json();

                if (!isset($body['access_token'])) {

                    Log::error('MTN getAccessToken failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return null;
                }

                return $body['access_token'];
            }
        );
    }

    /**
     * Request payment from user
     */
    public function requestPayment(
        string $phoneNumber,
        float $amount,
        string $externalId
    ): string {

        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return 'error: unable to generate access token';
        }

        /**
         * Sandbox only supports EUR
         */
        $currency = $this->env === 'sandbox'
            ? 'EUR'
            : $this->currency;

        $amount = (int) round($amount);

        $url = $this->baseUrl() . '/collection/v1_0/requesttopay';

        /**
         * MTN Reference ID
         */
        $referenceId = (string) Str::uuid();

        $payload = [
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => $externalId,

            'payer' => [
                'partyIdType' => 'MSISDN',
                'partyId' => $this->formatPhoneNumber($phoneNumber),
            ],

            'payerMessage' => "Payment for order {$externalId}",
            'payeeNote' => 'Thank you for your payment',
        ];

        try {

            $response = Http::retry(3, 1000)
                ->timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$accessToken}",
                    'X-Reference-Id' => $referenceId,
                    'X-Target-Environment' => $this->targetEnvironment(),
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'X-Callback-Url' => $this->callbackUrl,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post($url, $payload);

            Log::info('MTN RequestToPay Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);

            /**
             * 202 = accepted
             */
            if ($response->status() === 202) {
                return $referenceId;
            }

            $body = $response->json();

            $message = $body['message']
                ?? $response->body()
                ?? 'Unknown error';

            Log::error('MTN RequestToPay Failed', [
                'message' => $message,
                'payload' => $payload,
            ]);

            return "error: {$message}";

        } catch (\Throwable $e) {

            Log::error('MTN RequestToPay Exception', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return 'error: payment request exception';
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $referenceId): array
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {

            return [
                'status' => 'ERROR',
                'message' => 'Unable to generate access token',
            ];
        }

        $url = $this->baseUrl()
            . "/collection/v1_0/requesttopay/{$referenceId}";

        try {

            $response = Http::retry(3, 1000)
                ->timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$accessToken}",
                    'X-Target-Environment' => $this->targetEnvironment(),
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'Accept' => 'application/json',
                ])->get($url);

            if ($response->failed()) {

                Log::error('MTN getPaymentStatus failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'reference_id' => $referenceId,
                ]);
            }

            return $response->json();

        } catch (\Throwable $e) {

            Log::error('MTN getPaymentStatus exception', [
                'message' => $e->getMessage(),
                'reference_id' => $referenceId,
            ]);

            return [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
    }
}