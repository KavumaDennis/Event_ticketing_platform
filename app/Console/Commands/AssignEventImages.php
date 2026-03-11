<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AssignEventImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:assign-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign images from public/events to all events and update storage paths';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting image assignment process...');

        // 1. Load all events (we overwrite any existing URLs/paths)
        $events = Event::orderBy('id')->get();

        if ($events->isEmpty()) {
            $this->info('No events found without images.');
            return;
        }

        $this->info("Found {$events->count()} events to update.");

        // 2. Get images from public/events
        $sourceDir = public_path('events');
        if (!File::exists($sourceDir)) {
            $this->error("Source directory not found: {$sourceDir}");
            return;
        }

        $files = File::files($sourceDir);
        // Filter for images only
        $images = array_filter($files, function ($file) {
            return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        });

        if (empty($images)) {
            $this->error('No images found in public/events.');
            return;
        }

        // Reset array keys to 0, 1, 2... for easy modulo
        $images = array_values($images);
        $imageCount = count($images);
        $this->info("Found {$imageCount} source images.");

        // 3. Ensure destination directory exists in STORAGE (not public directly)
        // Laravel's public disk usually points to storage/app/public
        // We want to store in 'events' folder within that disk.
        if (!Storage::disk('public')->exists('events')) {
            Storage::disk('public')->makeDirectory('events');
        }

        $bar = $this->output->createProgressBar($events->count());
        $bar->start();

        foreach ($events as $index => $event) {
            // cycle through images
            $sourceImage = $images[$index % $imageCount];
            $extension = $sourceImage->getExtension();
            
            // Naming convention: event_{id}_{timestamp}_{index}.{ext}
            $filename = "event_{$event->id}_" . time() . "_{$index}.{$extension}";
            $destinationPath = 'events/' . $filename;

            // Copy file using Storage facade to ensure it goes to the correct disk location
            // We use file_get_contents because Storage::put expects content or a resource
            try {
                Storage::disk('public')->put($destinationPath, file_get_contents($sourceImage->getPathname()));

                // Update Database
                $event->event_image = $destinationPath;
                $event->save();

            } catch (\Exception $e) {
                $this->error("Failed to assign image to Event ID {$event->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('All events have been assigned images successfully.');
        $this->info('Run "php artisan storage:link" if images do not appear.');
    }
}
