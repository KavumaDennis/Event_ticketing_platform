<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;
use App\Models\User;
use App\Models\Event;

class LikeSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray();
        $events = Event::pluck('id')->toArray();

        $existing = Like::pluck('event_id', 'user_id')->toArray(); // Track existing DB likes
        $count = 0; // Number of likes inserted

        while ($count < 200) {
            $userId = $users[array_rand($users)];
            $eventId = $events[array_rand($events)];

            // Skip if like already exists in DB or in this run
            if (Like::where('user_id', $userId)->where('event_id', $eventId)->exists() || isset($existing["$userId-$eventId"])) {
                continue;
            }

            // Create the like
            Like::create([
                'user_id' => $userId,
                'event_id' => $eventId,
            ]);

            // Mark as existing in current run
            $existing["$userId-$eventId"] = true;

            $count++;
        }
    }
}
