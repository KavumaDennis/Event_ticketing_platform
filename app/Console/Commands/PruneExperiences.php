<?php

namespace App\Console\Commands;

use App\Models\Experience;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneExperiences extends Command
{
    protected $signature = 'experiences:prune';

    protected $description = 'Delete expired experiences and their media files';

    public function handle(): int
    {
        $expired = Experience::where('expires_at', '<=', now())->get();

        if ($expired->isEmpty()) {
            $this->info('No expired experiences found.');
            return Command::SUCCESS;
        }

        $this->info("Pruning {$expired->count()} expired experiences...");

        foreach ($expired as $experience) {
            if ($experience->media_path && Storage::disk('public')->exists($experience->media_path)) {
                Storage::disk('public')->delete($experience->media_path);
            }

            $experience->delete();
        }

        $this->info('Expired experiences pruned successfully.');
        return Command::SUCCESS;
    }
}
