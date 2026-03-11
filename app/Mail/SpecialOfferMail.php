<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use App\Models\User;

class SpecialOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $user;
    public $organizer;

    public function __construct(Event $event, User $user)
    {
        $this->event = $event;
        $this->user = $user;
        $this->organizer = $event->organizer;
    }

    public function build()
    {
        return $this->subject('Special Offer: ' . $this->event->event_name)
            ->view('emails.special_offer');
    }
}
