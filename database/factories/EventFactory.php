<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Event::class;
    public function definition(): array
    {

        $start = $this->faker->dateTimeBetween('+0 days','+1 month');
        $end = (clone $start)->modify('+'.rand(1,6).'hours');

        // Get a random organizer or create one if none exists
        $organizer = Organizer::inRandomOrder()->first();
        if (!$organizer) {
            // If no organizers exist, we'll set organizer_id to null
            // You should seed organizers first
            $organizerId = null;
        } else {
            $organizerId = $organizer->id;
        }

        return [
            //
            'event_name' => $this->faker->sentence(3),
            'organizer_id' => $organizerId,
            'category' => $this->faker->randomElement(['Music', 'Sports', 'Arts', 'Technology', 'Business', 'Food', 'Other']),
            'location' => $this->faker->city(),
            'venue' => $this->faker->company(),
            'event_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'description' => $this->faker->paragraph(),
            'event_image' => 'events/default.jpg',
            'regular_price' => $this->faker->randomFloat(2, 500, 5000),
            'regular_quantity' => $this->faker->numberBetween(50, 200),
            'vip_price' => $this->faker->randomFloat(2,5000,10000),
            'vip_quantity' => $this->faker->numberBetween(10, 50),
            'vvip_price' => $this->faker->randomFloat(2, 10000, 20000),
            'vvip_quantity' => $this->faker->numberBetween(5, 20),
        ];
    }
}
