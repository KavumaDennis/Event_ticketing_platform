<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class GenerateUsersSeeder extends Seeder
{
    public function run()
    {
        User::factory()->count(20)->create();
    }
}
