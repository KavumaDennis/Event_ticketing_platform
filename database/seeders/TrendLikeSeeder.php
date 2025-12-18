<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrendLike;
use App\Models\User;
use App\Models\Trend;

class TrendLikeSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray();
        $trends = Trend::pluck('id')->toArray();

        // Load existing trend-user pairs from DB
        $existing = TrendLike::get()->mapWithKeys(function ($like) {
            return [$like->trend_id . '-' . $like->user_id => true];
        })->toArray();

        $total = 200; // how many new likes you want to add
        $count = 0;
        $maxAttempts = 1000; // avoid infinite loop
        $attempts = 0;

        while ($count < $total && $attempts < $maxAttempts) {
            $userId = $users[array_rand($users)];
            $trendId = $trends[array_rand($trends)];

            $key = $trendId . '-' . $userId;

            if (isset($existing[$key])) {
                $attempts++;
                continue; // skip duplicates
            }

            TrendLike::create([
                'user_id' => $userId,
                'trend_id' => $trendId,
            ]);

            $existing[$key] = true;
            $count++;
        }

        if ($attempts >= $maxAttempts) {
            $this->command->info('Reached max attempts while seeding TrendLikes. Some duplicates were skipped.');
        }
    }
}
