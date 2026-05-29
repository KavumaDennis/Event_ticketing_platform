<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'sender_id', 'body', 'type', 'event_id', 'reply_to_id'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }

    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function reactions()
    {
        return $this->hasMany(MessageReaction::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }
}
