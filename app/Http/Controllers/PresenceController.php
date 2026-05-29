<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PresenceController extends Controller
{
    public function ping(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['ok' => false], 401);
        }

        $key = $this->key($user->id);
        Cache::put($key, now()->timestamp, now()->addMinutes(5));

        return response()->json(['ok' => true]);
    }

    public function status(Request $request, User $user)
    {
        $ts = Cache::get($this->key($user->id));
        $lastSeen = $ts ? now()->setTimestamp((int) $ts) : null;
        $online = $lastSeen ? $lastSeen->gt(now()->subSeconds(45)) : false;

        return response()->json([
            'user_id' => $user->id,
            'online' => $online,
            'last_seen_at' => $lastSeen?->toIso8601String(),
            'last_seen_human' => $lastSeen?->shortAbsoluteDiffForHumans(),
        ]);
    }

    private function key(int $userId): string
    {
        return 'presence:last_seen:' . $userId;
    }
}

