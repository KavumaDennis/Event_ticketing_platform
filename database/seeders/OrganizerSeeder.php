<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organizer;

class OrganizerSeeder extends Seeder
{
    public function run()
    {
        Organizer::factory()->count(10)->create();
    }
}
