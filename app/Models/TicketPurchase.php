<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPurchase extends Model
{
    protected $fillable = [
        'user_id',        // optional but required for dashboard
        'event_id',
        'ticket_type',
        'quantity',
        'total',
        'currency',
        'phone',

        // MTN identifiers
        'reference_id',   // UUID (X-Reference-Id)
        'external_id',    // MTN callback identifier

        'status',         // pending, paid, failed
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /* ===================== RELATIONSHIPS ===================== */

    // Each purchase belongs to an event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Purchase owner (dashboard, orders page)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A purchase can have many tickets (quantity based)
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /* ===================== HELPERS ===================== */

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
