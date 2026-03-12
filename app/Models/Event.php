<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'category',
        'organizer_id', // foreign key
        'location',
        'venue',
        'event_date',
        'start_time',
        'end_time',
        'description',
        'event_image',
        'regular_price',
        'regular_quantity',
        'vip_price',
        'vip_quantity',
        'vvip_price',
        'vvip_quantity',
        'is_boosted',
        'boosted_until',
    ];

    protected $casts = [
        'is_boosted' => 'boolean',
        'boosted_until' => 'datetime',
        'event_date' => 'date',
        'regular_price' => 'integer',
        'vip_price' => 'integer',
        'vvip_price' => 'integer',
        'regular_quantity' => 'integer',
        'vip_quantity' => 'integer',
        'vvip_quantity' => 'integer',
    ];

    // app/Models/Event.php

    public function organizer()
    {
        // Link events.organizer_id → organizers.id
        return $this->belongsTo(Organizer::class, 'organizer_id', 'id');
    }



    /**
     * Likes relationship
     */
    public function likes()
    {
        return $this->hasMany(Like::class); // ✅ Removed the stray comma
    }

    public function comments()
    {
        return $this->hasMany(EventComment::class)->latest();
    }

    /**
     * Check if the event is liked by a specific user
     */
    public function isLikedBy($user)
    {
        if (!$user)
            return false; // ✅ Prevent error for guests
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    // app/Models/Event.php
    public function savedBy()
    {
        return $this->belongsToMany(User::class, 'saved_events', 'event_id', 'user_id');
    }

    public function isSavedBy($user = null)
    {
        $user = $user ?? Auth::user();
        if (!$user)
            return false;

        return $this->savedBy()->where('user_id', $user->id)->exists();
    }

    // Event.php
    // One event has many trends
    public function trends()
    {
        return $this->hasMany(Trend::class);
    }

    public function ticketTypes()
{
    return $this->hasMany(TicketType::class);
}

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketPurchases()
    {
        return $this->hasMany(TicketPurchase::class);
    }

    /* ===================== MONETIZATION HELPERS ===================== */

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function isHot()
    {
        $threshold = config('monetization.hot_threshold', 50);
        return $this->likesCount() >= $threshold || $this->trendingScore() >= $threshold;
    }

    public function trendingScore()
    {
        // This will be calculated by a service, but we can expose it here
        return app(\App\Services\TrendRankingService::class)->calculateEventScore($this);
    }

    public function getVisibilityScore()
    {
        return app(\App\Services\TrendRankingService::class)->calculateEventScore($this);
    }

    public function isBoostActive()
    {
        return $this->is_boosted && (!$this->boosted_until || $this->boosted_until->isFuture());
    }

    /* ===================== SCOPES ===================== */

    public function scopeWithVisibilityScore($query)
    {
        // Prioritize:
        // 1. Paid Boosts
        // 2. High Likes (Organic Boost)
        // 3. Recency
        return $query->withCount('likes')
            ->orderBy('is_boosted', 'desc')
            ->orderBy('likes_count', 'desc')
            ->orderBy('created_at', 'desc');
    }

    public function scopeOrganizedBy($query, $user)
    {
        return $query->whereHas('organizer', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    public function isSoldOut()
    {
        $sold = $this->ticketPurchases()->where('status', 'paid')->sum('quantity');
        $total = ($this->regular_quantity ?? 0) + ($this->vip_quantity ?? 0) + ($this->vvip_quantity ?? 0);
        
        if ($total === 0) return false;

        return $sold >= $total;
    }

    public function waitlist()
    {
        return $this->hasMany(Waitlist::class);
    }

    public function views()
    {
        return $this->hasMany(EventView::class);
    }
}

