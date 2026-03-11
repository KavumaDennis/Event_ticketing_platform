<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventView extends Model
{
    protected $fillable = ['event_id', 'ip_address'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
