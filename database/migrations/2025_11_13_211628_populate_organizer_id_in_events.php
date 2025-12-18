<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Match by business_name if both tables have that
        DB::table('events')->orderBy('id')->chunk(100, function ($events) {
            foreach ($events as $event) {
                $organizer = DB::table('organizers')
                    ->where('business_name', $event->organizer_name ?? '')
                    ->first();

                if ($organizer) {
                    DB::table('events')
                        ->where('id', $event->id)
                        ->update(['organizer_id' => $organizer->id]);
                }
            }
        });
    }

    public function down(): void
    {
        DB::table('events')->update(['organizer_id' => null]);
    }
};

