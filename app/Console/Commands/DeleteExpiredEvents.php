<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteExpiredEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hard delete events 5 days after their expiration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = \App\Models\Event::whereDate('event_date', '<=', now()->subDays(5))->get();
        $count = $events->count();

        foreach ($events as $event) {
            if ($event->event_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->event_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->event_image);
            }
            $event->delete();
        }

        $this->info("Deleted {$count} expired events.");
    }
}
