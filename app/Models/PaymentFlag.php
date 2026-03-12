<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentFlag extends Model
{
    protected $fillable = [
        'ticket_purchase_id',
        'type',
        'status',
        'source',
        'reason',
        'admin_notes',
        'flagged_by',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function purchase()
    {
        return $this->belongsTo(TicketPurchase::class, 'ticket_purchase_id');
    }

    public function flaggedBy()
    {
        return $this->belongsTo(User::class, 'flagged_by');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
