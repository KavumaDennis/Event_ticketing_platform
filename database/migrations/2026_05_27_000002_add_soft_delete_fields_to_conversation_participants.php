<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversation_participants', function (Blueprint $table) {
            $table->timestamp('cleared_at')->nullable()->after('role');
            $table->timestamp('deleted_at')->nullable()->after('cleared_at');
        });
    }

    public function down(): void
    {
        Schema::table('conversation_participants', function (Blueprint $table) {
            $table->dropColumn(['deleted_at', 'cleared_at']);
        });
    }
};

