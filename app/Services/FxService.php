<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FxService
{
    public function quote(float $amount, string $from, string $to): array
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) {
            return [
                'rate' => 1.0,
                'converted' => round($amount, 2),
                'provider' => 'local',
                'timestamp' => now(),
            ];
        }

        $cacheKey = "fx_rate_{$from}_{$to}";
        $cacheMinutes = (int) config('monetization.fx.cache_minutes', 60);

        $rateData = Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), function () use ($from, $to) {
            $provider = config('monetization.fx.provider', 'exchangerate.host');

            if ($provider === 'exchangerate.host') {
                $response = Http::timeout(6)->get('https://api.exchangerate.host/latest', [
                    'base' => $from,
                    'symbols' => $to,
                ]);
            } else {
                $response = Http::timeout(6)->get($provider, [
                    'base' => $from,
                    'symbols' => $to,
                ]);
            }

            if (!$response->ok()) {
                return null;
            }

            $data = $response->json();
            $rate = $data['rates'][$to] ?? null;
            if (!$rate) {
                return null;
            }

            return [
                'rate' => (float) $rate,
                'provider' => $provider,
                'timestamp' => now(),
            ];
        });

        if (!$rateData) {
            return [
                'rate' => 1.0,
                'converted' => round($amount, 2),
                'provider' => 'fallback',
                'timestamp' => now(),
            ];
        }

        $converted = round($amount * $rateData['rate'], 2);

        return [
            'rate' => $rateData['rate'],
            'converted' => $converted,
            'provider' => $rateData['provider'],
            'timestamp' => $rateData['timestamp'],
        ];
    }
}
