<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for saved events happening soon';

    public function handle()
    {
        $tomorrow = now()->addDay()->format('Y-m-d');

        $savedEvents = \App\Models\SavedEvent::whereHas('event', function ($query) use ($tomorrow) {
            $query->whereDate('event_date', $tomorrow);
        })->with(['user', 'event'])->get();

        $notificationService = new \App\Services\NotificationService();

        foreach ($savedEvents as $saved) {
            $event = $saved->event;
            $user = $saved->user;

            $this->info("Sending reminder for event: {$event->event_name} to user: {$user->email}");

            $notificationService->send(
                $user,
                "Upcoming Event Reminder",
                "Don't forget! '{$event->event_name}' is happening tomorrow at {$event->venue}.",
                "info",
                $event,
                route('event.show', $event->id)
            );
        }

        $this->info("Sent reminders for " . $savedEvents->count() . " events.");
    }
}
