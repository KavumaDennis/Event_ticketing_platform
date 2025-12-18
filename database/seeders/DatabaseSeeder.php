<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 👤 Create or update a single test user safely
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'password' => bcrypt('password'),
            ]
        );

        // 📅 Seed events (only if table is empty)
        if (\App\Models\Event::count() === 0) {
            $this->call(EventSeeder::class);
        }

        // 📰 Seed trends (only if table is empty)
        if (\App\Models\Trend::count() === 0) {
            $this->call(TrendSeeder::class);
        }


        // 📰 Seed likes (only if table is empty)

        $this->call([
            LikesTableSeeder::class,
        ]);

        $this->call([
            OrganizerFollowerSeeder::class,
        ]);

        $this->call([
            UserFollowSeeder::class,
        ]);

        $this->call(
            GenerateUsersSeeder::class
        );






    }
}
