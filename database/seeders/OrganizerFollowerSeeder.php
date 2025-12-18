<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organizer;
use App\Models\OrganizerFollower;
use Illuminate\Support\Facades\DB;

class OrganizerFollowerSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray();
        $organizers = Organizer::pluck('id')->toArray();

        $existing = DB::table('organizer_followers')
            ->select('user_id', 'organizer_id')
            ->get()
            ->map(fn($row) => $row->user_id . '-' . $row->organizer_id)
            ->toArray();

        $existing = array_flip($existing);

        $count = 0;
        while ($count < 200) { // Generate 200 follower relationships
            $userId = $users[array_rand($users)];
            $organizerId = $organizers[array_rand($organizers)];

            $key = $userId . '-' . $organizerId;

            if (isset($existing[$key])) {
                continue; // Skip duplicates
            }

            OrganizerFollower::create([
                'user_id' => $userId,
                'organizer_id' => $organizerId,
            ]);

            $existing[$key] = true;
            $count++;
        }
    }
}
