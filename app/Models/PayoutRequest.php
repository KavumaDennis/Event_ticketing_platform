<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    protected $fillable = [
        'organizer_id',
        'amount',
        'payment_method',
        'payment_details',
        'status',
        'admin_note',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
}
