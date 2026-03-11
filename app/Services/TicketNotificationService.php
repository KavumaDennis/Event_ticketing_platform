<?php

namespace App\Services;

use App\Models\TicketPurchase;
use App\Mail\TicketPurchased;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TicketNotificationService
{
    /**
     * Send ticket via Email
     */
    public function sendTicketEmail(TicketPurchase $purchase)
    {
        try {
            $recipient = $purchase->user ? $purchase->user->email : null;

            if (!$recipient) {
                Log::warning("No email address found for Purchase ID: {$purchase->id}. Skipping email delivery.");
                return false;
            }

            Mail::to($recipient)->send(new TicketPurchased($purchase));
            
            Log::info("Ticket delivery email sent for Purchase ID: {$purchase->id} to {$recipient}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send ticket email for Purchase ID: {$purchase->id}. Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send ticket via WhatsApp using Twilio
     */
    public function sendTicketWhatsApp(TicketPurchase $purchase)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.whatsapp_from');

        if (!$sid || !$token || !$from) {
            Log::warning("Twilio API credentials not set. Skipping WhatsApp delivery for Purchase ID: {$purchase->id}");
            return false;
        }

        try {
            $to = "whatsapp:" . preg_replace('/[^0-9]/', '', $purchase->phone);
            $fromFormatted = "whatsapp:" . preg_replace('/[^0-9]/', '', $from);

            $message = "Your ticket for {$purchase->event->event_name} is ready! \n\n" .
                       "Date: {$purchase->event->event_date} \n" .
                       "Time: {$purchase->event->event_time} \n" .
                       "Order Ref: {$purchase->reference_id} \n\n" .
                       "View your ticket here: " . route('ticket.view', $purchase->id);

            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'To' => $to,
                    'From' => $fromFormatted,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                Log::info("Twilio WhatsApp notification sent for Purchase ID: {$purchase->id}");
                return true;
            }

            Log::error("Twilio API error for Purchase ID: {$purchase->id}. Status: {$response->status()}, Response: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("Failed to send Twilio WhatsApp notification for Purchase ID: {$purchase->id}. Error: " . $e->getMessage());
            return false;
        }
    }
}
