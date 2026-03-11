<?php

namespace App\Jobs;

use App\Models\TicketPurchase;
use App\Services\TicketNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTicketNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $purchase;

    /**
     * Create a new job instance.
     */
    public function __construct(TicketPurchase $purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * Execute the job.
     */
    public function handle(TicketNotificationService $service): void
    {
        // Send email
        $service->sendTicketEmail($this->purchase);

        // Send WhatsApp
        $service->sendTicketWhatsApp($this->purchase);
    }
}
