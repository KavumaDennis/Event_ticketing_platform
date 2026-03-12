<?php

namespace App\Observers;

use App\Models\TicketPurchase;
use App\Models\PaymentFlag;
use Illuminate\Support\Facades\Schema;

class TicketPurchaseObserver
{
    /**
     * Handle the TicketPurchase "created" event.
     */
    public function created(TicketPurchase $ticketPurchase): void
    {
        $thresholds = config('monetization.fraud', []);
        $maxQty = $thresholds['max_quantity'] ?? 10;
        $maxTotalBase = $thresholds['max_total_base'] ?? 1000000;
        $maxPerDay = $thresholds['max_purchases_per_day'] ?? 3;

        $totalBase = $ticketPurchase->total_base
            ?? (($ticketPurchase->base_total ?? 0) + ($ticketPurchase->service_fee ?? 0));

        $reasons = [];
        if ($ticketPurchase->quantity >= $maxQty) {
            $reasons[] = "High quantity: {$ticketPurchase->quantity}";
        }
        if ($totalBase >= $maxTotalBase) {
            $reasons[] = "High total base amount: {$totalBase}";
        }

        if ($ticketPurchase->user_id) {
            $todayCount = TicketPurchase::where('user_id', $ticketPurchase->user_id)
                ->whereDate('created_at', now()->toDateString())
                ->count();
            if ($todayCount > $maxPerDay) {
                $reasons[] = "High daily purchases: {$todayCount}";
            }
        }

        if (!empty($reasons) && Schema::hasTable('payment_flags')) {
            PaymentFlag::create([
                'ticket_purchase_id' => $ticketPurchase->id,
                'type' => 'fraud',
                'status' => 'open',
                'source' => 'system',
                'reason' => implode(' | ', $reasons),
            ]);
        }
    }

    /**
     * Handle the TicketPurchase "updated" event.
     */
    public function updated(TicketPurchase $ticketPurchase): void
    {
        if ($ticketPurchase->isDirty('status') && $ticketPurchase->status === 'paid') {
            $event = $ticketPurchase->event;
            $user = $ticketPurchase->user;
            
            // --- 1. Friend Activity Notification ---
            // Notify followers that this user is attending an event
            if ($user) {
                $followers = $user->followers()->with('follower')->get();
                $notificationService = new \App\Services\NotificationService();
                
                foreach ($followers as $follow) {
                    $followerUser = $follow->follower;
                    if ($followerUser) {
                        $notificationService->send(
                            $followerUser,
                            "Friend Activity",
                            "{$user->first_name} is attending '{$event->event_name}'",
                            "info",
                            $event,
                            route('event.show', $event->id)
                        );
                    }
                }
            }

            $type = $ticketPurchase->ticket_type;
            
            // Calculate remaining tickets
            $totalQty = $event->{$type . '_quantity'};
            $soldQty = \App\Models\TicketPurchase::where('event_id', $event->id)
                ->where('ticket_type', $type)
                ->where('status', 'paid')
                ->sum('quantity');
                
            $remaining = $totalQty - $soldQty;
            
            // Threshold for low tickets (e.g., 10)
            if ($remaining <= 10 && $remaining > 0) {
                // ... existing low ticket logic ...
                // Re-instantiate service if not already existing (or reuse if moved up)
                $notificationService = $notificationService ?? new \App\Services\NotificationService();
                
                // Notify Organizer
                $organizerUser = $event->organizer->user;
                if ($organizerUser) {
                    $notificationService->send(
                        $organizerUser,
                        "Low Ticket Alert: {$event->event_name}",
                        "Only {$remaining} {$type} tickets left for your event!",
                        "warning",
                        $event,
                        route('event.show', $event->id)
                    );
                }
                
                // Notify Users who saved the event (Scarcity Marketing)
                if (in_array($remaining, [10, 5, 1])) {
                    $savedUsers = \App\Models\SavedEvent::where('event_id', $event->id)
                        ->with('user')
                        ->get()
                        ->pluck('user');
                        
                    foreach ($savedUsers as $user) {
                        $notificationService->send(
                            $user,
                            "Hurry! Tickets Running Low",
                            "Only {$remaining} tickets left for '{$event->event_name}'. Secure yours now!",
                            "warning",
                            $event,
                            route('event.show', $event->id)
                        );
                    }
                }
            }
        }
    }

    /**
     * Handle the TicketPurchase "deleted" event.
     */
    public function deleted(TicketPurchase $ticketPurchase): void
    {
        //
    }

    /**
     * Handle the TicketPurchase "restored" event.
     */
    public function restored(TicketPurchase $ticketPurchase): void
    {
        //
    }

    /**
     * Handle the TicketPurchase "force deleted" event.
     */
    public function forceDeleted(TicketPurchase $ticketPurchase): void
    {
        //
    }
}
