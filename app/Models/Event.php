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





}

