<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trend;
use App\Models\TrendMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AssignTrendMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trends:assign-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign media (images/videos) from public/trends to all trends and update storage paths';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting trend media assignment process...');

        // 1. Load all trends (we overwrite any existing URLs/paths)
        $trends = Trend::orderBy('id')->get();

        if ($trends->isEmpty()) {
            $this->info('No trends found without media.');
            return;
        }

        $this->info("Found {$trends->count()} trends to update.");

        // 2. Get media from public/trends
        $sourceDir = public_path('trends');
        if (!File::exists($sourceDir)) {
            $this->error("Source directory not found: {$sourceDir}");
            return;
        }

        $files = File::files($sourceDir);
        // Filter for images AND videos
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'mp4', 'mov', 'avi', 'wmv', 'flv', 'webm'];
        
        $mediaFiles = array_filter($files, function ($file) use ($allowedExtensions) {
            return in_array(strtolower($file->getExtension()), $allowedExtensions);
        });

        if (empty($mediaFiles)) {
            $this->error('No compatible media found in public/trends.');
            return;
        }

        // Reset array keys
        $mediaFiles = array_values($mediaFiles);
        $mediaCount = count($mediaFiles);
        $this->info("Found {$mediaCount} source media files.");

        // 3. Ensure destination directory exists in STORAGE
        if (!Storage::disk('public')->exists('trends')) {
            Storage::disk('public')->makeDirectory('trends');
        }

        $bar = $this->output->createProgressBar($trends->count());
        $bar->start();

        foreach ($trends as $index => $trend) {
            // Cycle through media evenly
            $sourceFile = $mediaFiles[$index % $mediaCount];
            $extension = $sourceFile->getExtension();
            
            // Naming convention: trend_{id}_{timestamp}_{index}.{ext}
            $filename = "trend_{$trend->id}_" . time() . "_{$index}.{$extension}";
            $destinationPath = 'trends/' . $filename;

            try {
                Storage::disk('public')->put($destinationPath, file_get_contents($sourceFile->getPathname()));

                // Remove old media + legacy image field
                $trend->media()->delete();
                $trend->image = null;
                $trend->save();

                // Create new media row
                $type = in_array(strtolower($extension), ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm']) ? 'video' : 'image';
                TrendMedia::create([
                    'trend_id' => $trend->id,
                    'path' => $destinationPath,
                    'type' => $type,
                    'order' => 0,
                ]);

            } catch (\Exception $e) {
                $this->error("Failed to assign media to Trend ID {$trend->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('All trends have been assigned media successfully.');
        $this->info('Run "php artisan storage:link" if media does not appear.');
    }
}
