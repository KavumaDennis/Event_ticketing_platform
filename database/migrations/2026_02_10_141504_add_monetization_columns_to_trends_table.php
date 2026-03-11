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
        Schema::table('trends', function (Blueprint $table) {
            $table->boolean('is_sponsored')->default(false)->after('image');
            $table->integer('boost_level')->default(0)->after('is_sponsored');
            $table->timestamp('boost_expires_at')->nullable()->after('boost_level');
            $table->string('location')->nullable()->after('boost_expires_at');
            $table->json('interest_tags')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trends', function (Blueprint $table) {
            $table->dropColumn(['is_sponsored', 'boost_level', 'boost_expires_at', 'location', 'interest_tags']);
        });
    }
};
