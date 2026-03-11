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
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('related_id')->nullable()->after('type'); // ID of the related model (Event, Trend, User)
            $table->string('related_type')->nullable()->after('related_id'); // Class name of the related model
            $table->string('action_url')->nullable()->after('related_type'); // Direct URL if needed
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['related_id', 'related_type', 'action_url']);
        });
    }
};
