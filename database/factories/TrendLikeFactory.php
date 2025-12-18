<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Trend;
use App\Models\TrendLike;

class TrendLikeFactory extends Factory
{
    protected $model = TrendLike::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'trend_id' => Trend::inRandomOrder()->first()->id,
        ];
    }
}
