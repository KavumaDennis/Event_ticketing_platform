<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OrganizerFollower;
use App\Models\User;
use App\Models\Organizer;

class OrganizerFollowerFactory extends Factory
{
    protected $model = OrganizerFollower::class;

    public function definition()
    {
        $userId = User::inRandomOrder()->first()->id;
        $organizerId = Organizer::inRandomOrder()->first()->id;

        return [
            'user_id' => $userId,
            'organizer_id' => $organizerId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
