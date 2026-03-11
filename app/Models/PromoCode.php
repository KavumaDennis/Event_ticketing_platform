<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_amount',
        'event_id',
        'organizer_id',
        'usage_limit',
        'used_count',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function isValid()
    {
        if (!$this->status) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        return true;
    }
}
