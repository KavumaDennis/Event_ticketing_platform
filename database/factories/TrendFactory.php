<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Trend;
use App\Models\User;
use App\Models\Event;

class TrendFactory extends Factory
{
    protected $model = Trend::class;

    public function definition()
    {
        // Pick a random user
        $userId = User::inRandomOrder()->first()->id;

        // Pick a random event or null if no events exist
        $event = Event::inRandomOrder()->first();
        $eventId = $event ? $event->id : null;

        return [
            'user_id' => $userId,
            'event_id' => $eventId,
            'title' => $this->faker->sentence(6),
            'body' => $this->faker->paragraph(3),
            'image' => null, // optionally add fake images
        ];
    }
}
