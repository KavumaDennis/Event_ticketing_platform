<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_purchase_id',
        'event_id',
        'ticket_code',
        'ticket_type',
        'quantity',
        'qr_code_path',
    ];

    // Each ticket belongs to a purchase
    public function purchase()
    {
        return $this->belongsTo(TicketPurchase::class, 'ticket_purchase_id');
    }

    // Each ticket belongs to an event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // A ticket can have many transfer attempts
    public function transfers()
    {
        return $this->hasMany(TicketTransfer::class);
    }

    public function isPendingTransfer()
    {
        return $this->transfers()->where('status', 'pending')->exists();
    }

    public function latestAcceptedTransfer()
    {
        return $this->hasOne(TicketTransfer::class)->where('status', 'accepted')->latestOfMany();
    }
}
