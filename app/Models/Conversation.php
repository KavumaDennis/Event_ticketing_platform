<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['is_group', 'name', 'event_id'];

    protected $casts = [
        'is_group' => 'boolean',
    ];

    public function participants()
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
