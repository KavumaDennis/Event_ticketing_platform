<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketPurchased extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase;

    public function __construct($purchase)
    {
        $this->purchase = $purchase;
    }

    public function build()
    {
        return $this->subject('Your Ticket for ' . $this->purchase->event->event_name)
            ->view('emails.ticket_purchased')
            ->with([
                'purchase' => $this->purchase,
                'event' => $this->purchase->event,
                'tickets' => $this->purchase->tickets,
            ]);
    }
}
