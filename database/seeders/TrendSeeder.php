<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trend;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str;

class TrendSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users (or create one if none exist)
        $users = User::all();

        if ($users->count() === 0) {
            $users = collect([
                User::factory()->create([
                    'first_name' => 'Default',
                    'last_name' => 'User',
                    'email' => 'default@example.com',
                    'password' => bcrypt('password'),
                ])
            ]);
        }

        // Get all events
        $events = Event::all();

        // Generate 50 fake trends
        for ($i = 1; $i <= 50; $i++) {
            Trend::create([
                'user_id' => $users->random()->id,
                'event_id' => $events->isNotEmpty() ? $events->random()->id : null, // assign event
                'title' => 'Trend #' . $i . ' — ' . Str::title(fake()->words(3, true)),
                'body' => fake()->paragraphs(3, true),
                'image' => null, // optionally assign fake image
            ]);
        }
    }
}
