<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateOrganizerIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder populates the organizer_id field for events that don't have one.
     */
    public function run(): void
    {
        // Get all events without organizer_id
        $eventsWithoutOrganizer = Event::whereNull('organizer_id')->get();
        
        if ($eventsWithoutOrganizer->isEmpty()) {
            $this->command->info('All events already have organizer_id assigned.');
            return;
        }

        $this->command->info("Found {$eventsWithoutOrganizer->count()} events without organizer_id. Populating...");

        $organizers = Organizer::all();
        
        if ($organizers->isEmpty()) {
            $this->command->warn('No organizers found in database. Cannot populate organizer_id.');
            return;
        }

        $assigned = 0;
        $skipped = 0;

        foreach ($eventsWithoutOrganizer as $event) {
            $organizer = null;

            // Strategy 1: Try to match by user_id if events have user_id column
            try {
                if (property_exists($event, 'user_id') || $event->getAttributes()['user_id'] ?? null) {
                    $userId = $event->user_id ?? $event->getAttributes()['user_id'] ?? null;
                    if ($userId) {
                        $organizer = Organizer::where('user_id', $userId)->first();
                    }
                }
            } catch (\Exception $e) {
                // user_id column might not exist, continue to next strategy
            }

            // Strategy 2: If no match by user_id, assign a random organizer
            if (!$organizer) {
                $organizer = $organizers->random();
            }

            if ($organizer) {
                $event->organizer_id = $organizer->id;
                $event->save();
                $assigned++;
                $this->command->info("Assigned organizer '{$organizer->business_name}' to event '{$event->event_name}' (ID: {$event->id})");
            } else {
                $skipped++;
                $this->command->warn("Could not assign organizer to event '{$event->event_name}' (ID: {$event->id})");
            }
        }

        $this->command->info("\nCompleted!");
        $this->command->info("Assigned: {$assigned} events");
        if ($skipped > 0) {
            $this->command->warn("Skipped: {$skipped} events");
        }
    }
}

