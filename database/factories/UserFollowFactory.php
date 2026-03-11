<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserFollow;
use App\Models\User;

class UserFollowFactory extends Factory
{
    protected $model = UserFollow::class;

    public function definition()
    {
        $followerId = null;
        $followingId = null;

        do {
            $follower = User::inRandomOrder()->first();
            $following = User::inRandomOrder()->first();

            $followerId = $follower->id;
            $followingId = $following->id;
        } while (
            $followerId === $followingId || // prevent self-follow
            UserFollow::where('follower_id', $followerId)
                      ->where('following_id', $followingId)
                      ->exists() // prevent duplicates
        );

        return [
            'follower_id' => $followerId,
            'following_id' => $followingId,
        ];
    }
}
