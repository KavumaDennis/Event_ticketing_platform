<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $trends = \DB::table('trends')->whereNotNull('image')->get();

        foreach ($trends as $trend) {
            $type = 'image';
            $extension = strtolower(pathinfo($trend->image, PATHINFO_EXTENSION));
            if (in_array($extension, ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm'])) {
                $type = 'video';
            }

            \DB::table('trend_media')->insert([
                'trend_id' => $trend->id,
                'path' => $trend->image,
                'type' => $type,
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('trend_media')->truncate();
    }
};
