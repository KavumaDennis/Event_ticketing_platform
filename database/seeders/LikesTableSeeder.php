<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Like;

class LikesTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $events = Event::all();

        if ($users->isEmpty() || $events->isEmpty()) {
            $this->command->info('No users or events to seed likes.');
            return;
        }

        // Loop through each event and randomly assign likes from users
        foreach ($events as $event) {
            // Randomly pick 1-5 users to like this event
            $randomUsers = $users->random(rand(1, min(5, $users->count())));

            foreach ($randomUsers as $user) {
                // Create a like if it doesn't already exist
                Like::firstOrCreate([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                ]);
            }
        }

        $this->command->info('Likes table seeded successfully!');
    }
}
