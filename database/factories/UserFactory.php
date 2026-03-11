<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'username'   => $this->faker->unique()->userName(),
            'email'      => $this->faker->unique()->safeEmail(),
            'bio'        => $this->faker->paragraph(),
            'profile_pic' => null, // or use avatar generator
            'email_verified_at' => now(),
            'password' => bcrypt('password'), 
            'remember_token' => Str::random(10),
        ];
    }
}
