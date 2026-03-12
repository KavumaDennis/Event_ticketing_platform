<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $organizers = DB::table('organizers')->select('id', 'user_id')->get();

        foreach ($organizers as $org) {
            DB::table('organizer_members')->updateOrInsert(
                [
                    'organizer_id' => $org->id,
                    'user_id' => $org->user_id,
                ],
                [
                    'role' => 'owner',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('organizer_members')->where('role', 'owner')->delete();
    }
};
