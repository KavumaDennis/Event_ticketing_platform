<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    public function broadcastWith(): array
    {
        $this->message->loadMissing(['sender', 'attachments', 'event', 'replyTo.sender']);

        $sender = $this->message->sender;
        $avatarPath = $sender?->profile_pic ?? $sender?->avatar;
        $avatarUrl = $avatarPath ? asset('storage/' . ltrim($avatarPath, '/')) : asset('default.png');

        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'sender' => $sender ? [
                'id' => $sender->id,
                'name' => trim(($sender->first_name ?? '') . ' ' . ($sender->last_name ?? '')) ?: ($sender->username ?? 'User'),
                'username' => $sender->username,
                'avatar' => $avatarUrl,
            ] : null,
            'body' => $this->message->body,
            'type' => $this->message->type,
            'event' => $this->message->event ? [
                'id' => $this->message->event->id,
                'title' => $this->message->event->event_name,
                'date' => $this->message->event->event_date,
                'location' => $this->message->event->location,
                'venue' => $this->message->event->venue,
                'banner' => $this->message->event->event_image ? asset('storage/' . $this->message->event->event_image) : asset('default.png'),
                'price' => $this->message->event->regular_price,
                'url' => route('event.show', $this->message->event->id),
            ] : null,
            'attachments' => $this->message->attachments?->map(fn ($a) => [
                'id' => $a->id,
                'type' => $a->type,
                'size' => $a->size,
                'url' => route('messages.attachments.download', $a->id),
                'view_url' => route('messages.attachments.view', $a->id),
            ])->all() ?? [],
            'reply_to' => $this->message->replyTo ? [
                'id' => $this->message->replyTo->id,
                'sender_name' => trim(($this->message->replyTo->sender?->first_name ?? '') . ' ' . ($this->message->replyTo->sender?->last_name ?? '')) ?: ($this->message->replyTo->sender?->username ?? 'User'),
                'body' => $this->message->replyTo->body,
                'type' => $this->message->replyTo->type,
            ] : null,
            'created_at' => $this->message->created_at?->toIso8601String(),
            'time' => $this->message->created_at?->shortAbsoluteDiffForHumans(),
        ];
    }
}
