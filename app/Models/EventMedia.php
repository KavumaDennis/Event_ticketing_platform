<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventMedia extends Model
{
    protected $fillable = [
        'event_id',
        'path',
        'type',
        'order',
        'width',
        'height',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getIsVideoAttribute()
    {
        return $this->type === 'video';
    }
}
