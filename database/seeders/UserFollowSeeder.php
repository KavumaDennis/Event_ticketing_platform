<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserFollow;

class UserFollowSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $pairs = [];

        foreach ($users as $follower) {
            foreach ($users as $following) {
                if ($follower->id !== $following->id) { // avoid self-follow
                    $key = $follower->id . '-' . $following->id;
                    if (!isset($pairs[$key])) {
                        $pairs[$key] = [
                            'follower_id' => $follower->id,
                            'following_id' => $following->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        // Randomize and take 50 unique pairs
        $uniquePairs = collect($pairs)->shuffle()->take(50)->values()->all();

        UserFollow::insert($uniquePairs);
    }
}
