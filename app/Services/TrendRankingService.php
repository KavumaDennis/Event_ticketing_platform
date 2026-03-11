<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Trend;
use Carbon\Carbon;

class TrendRankingService
{
    /**
     * Calculate the score for an event based on likes, purchases, and time.
     */
    public function calculateEventScore(Event $event)
    {
        $weights = config('monetization.ranking.weights') ?? [
            'likes' => 10,
            'purchases' => 50,
            'time_decay' => 0.1,
            'boost_multiplier' => 2.0,
        ];
        
        // 1. Base Score from Likes
        $likesScore = $event->likesCount() * ($weights['likes'] ?? 10);

        // 2. Score from Recent Purchases (last 48h)
        $recentPurchasesCount = $event->ticketPurchases()
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->subHours(48))
            ->count();
        $purchasesScore = $recentPurchasesCount * ($weights['purchases'] ?? 50);

        // 3. Paid Direct Boost (Massive Bonus)
        $directBoost = 0;
        if ($event->isBoostActive()) {
            $directBoost = 1000; // Fixed large boost for paid priority
        }

        // 4. Time Decay
        $hoursSinceCreation = $event->created_at->diffInHours(now());
        $decay = $hoursSinceCreation * ($weights['time_decay'] ?? 0.1);

        // 5. Organizer Tier Multiplier
        $multiplier = 1.0;
        if ($event->organizer) {
            $tier = $event->organizer->tier;
            $tierConfig = config("monetization.tiers.{$tier}");
            $multiplier = $tierConfig['trend_multiplier'] ?? 1.0;
        }

        $totalScore = ($likesScore + $purchasesScore + $directBoost) - $decay;
        
        return max(0, $totalScore * $multiplier);
    }

    /**
     * Calculate score for a trend item.
     */
    public function calculateTrendScore(Trend $trend)
    {
        $weights = config('monetization.ranking.weights') ?? [
            'likes' => 10,
            'purchases' => 50,
            'time_decay' => 0.1,
            'boost_multiplier' => 2.0,
        ];
        
        // Base score from likes on the trend itself
        $likesScore = $trend->likesCount() * ($weights['likes'] ?? 10);

        // If linked to an event, boost by event's success
        $eventBoost = 0;
        if ($trend->event) {
            $eventBoost = $this->calculateEventScore($trend->event) * 0.2; // 20% of event score
        }

        // Sponsored & Paid Boost
        $boostMultiplier = 1.0;
        if ($trend->boost_level > 0) {
            if (!$trend->boost_expires_at || $trend->boost_expires_at->isFuture()) {
                $boostMultiplier = 1 + ($trend->boost_level * ($weights['boost_multiplier'] ?? 2.0));
            }
        }

        $hoursSinceCreation = $trend->created_at->diffInHours(now());
        $decay = $hoursSinceCreation * ($weights['time_decay'] ?? 0.1);

        $totalScore = ($likesScore + $eventBoost) - $decay;

        return max(0, $totalScore * $boostMultiplier);
    }

    /**
     * Get sorted trends for a feed.
     */
    public function getTrendingFeed($limit = 20)
    {
        // Modified to prioritize likes as requested by user
        // We fetch trends, load relationships, and sort by likes count (descending)
        // If likes are equal, we use the calculated score as a tiebreaker
        return Trend::with(['event', 'user'])
            ->withCount('likes')
            ->get()
            ->sortByDesc(function($trend) {
                // Primary sort: Likes count
                // Secondary sort: Calculated score (recency, etc.)
                return $trend->likes_count * 1000 + $this->calculateTrendScore($trend);
            })
            ->take($limit);
    }
}
