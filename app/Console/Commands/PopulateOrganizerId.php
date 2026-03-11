<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Console\Command;

class PopulateOrganizerId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:populate-organizer-id 
                            {--force : Force update even if organizer_id is already set}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate organizer_id for events that don\'t have one assigned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Event::query();
        
        if (!$this->option('force')) {
            $query->whereNull('organizer_id');
        }

        $events = $query->get();
        
        if ($events->isEmpty()) {
            $this->info('All events already have organizer_id assigned.');
            return Command::SUCCESS;
        }

        $this->info("Found {$events->count()} events to process.");

        $organizers = Organizer::all();
        
        if ($organizers->isEmpty()) {
            $this->error('No organizers found in database. Please create organizers first.');
            return Command::FAILURE;
        }

        $this->info("Available organizers: {$organizers->count()}");

        $assigned = 0;
        $skipped = 0;
        $bar = $this->output->createProgressBar($events->count());
        $bar->start();

        foreach ($events as $event) {
            $organizer = null;

            // Strategy 1: Try to match by user_id if events have user_id column
            try {
                $userId = $event->user_id ?? $event->getAttributes()['user_id'] ?? null;
                if ($userId) {
                    $organizer = Organizer::where('user_id', $userId)->first();
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
            } else {
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Completed!");
        $this->info("✓ Assigned organizer_id to {$assigned} events");
        
        if ($skipped > 0) {
            $this->warn("⚠ Skipped {$skipped} events (could not assign organizer)");
        }

        return Command::SUCCESS;
    }
}

