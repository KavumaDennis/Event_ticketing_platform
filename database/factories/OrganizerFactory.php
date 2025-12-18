<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrganizerFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'business_name' => $this->faker->company(),
            'business_email' => $this->faker->unique()->companyEmail(),
            'business_website' => $this->faker->optional()->url(),
            'organizer_image' => null, // or fake placeholder image
        ];
    }
}
