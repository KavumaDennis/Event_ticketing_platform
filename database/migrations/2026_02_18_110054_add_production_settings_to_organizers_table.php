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
        Schema::table('organizers', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->text('ticket_instructions')->nullable();
            $table->string('payout_frequency')->default('monthly');
            $table->string('tax_id')->nullable();
            $table->string('google_analytics_id')->nullable();
            $table->string('facebook_pixel_id')->nullable();
            $table->boolean('is_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'linkedin_url',
                'ticket_instructions',
                'payout_frequency',
                'tax_id',
                'google_analytics_id',
                'facebook_pixel_id',
                'is_verified',
            ]);
        });
    }
};
