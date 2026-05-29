<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
    /**
     * OpenStreetMap-derived search via Photon (no API key required).
     */
    public function autocomplete(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['suggestions' => []]);
        }

        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'User-Agent' => config('app.name', 'EventsApp').'/1.0 (+'.config('app.url', '').')',
                    'Accept' => 'application/json',
                ])
                ->get('https://photon.komoot.io/api/', [
                    'q' => $q,
                    'limit' => 8,
                ]);

            if (! $response->successful()) {
                return response()->json(['suggestions' => []]);
            }

            $suggestions = [];
            foreach ($response->json('features', []) as $feature) {
                $props = $feature['properties'] ?? [];
                $coords = $feature['geometry']['coordinates'] ?? null;
                if (! is_array($coords) || count($coords) < 2) {
                    continue;
                }
                [$lon, $lat] = $coords;
                $name = trim((string) ($props['name'] ?? ''));
                $city = trim((string) ($props['city'] ?? $props['town'] ?? $props['district'] ?? ''));
                $country = trim((string) ($props['country'] ?? ''));
                $parts = array_filter([$name, $city ?: null, $country ?: null]);
                $label = $parts !== [] ? implode(', ', $parts) : (string) ($props['street'] ?? $q);

                $suggestions[] = [
                    'label' => $label !== '' ? $label : $q,
                    'lat' => (float) $lat,
                    'lon' => (float) $lon,
                ];
            }

            return response()->json(['suggestions' => $suggestions]);
        } catch (\Throwable) {
            return response()->json(['suggestions' => []]);
        }
    }
}
