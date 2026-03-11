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
            $table->string('ticket_custom_background')->nullable()->after('show_logo_in_ticket');
            $table->string('ticket_custom_logo')->nullable()->after('ticket_custom_background');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->dropColumn(['ticket_custom_background', 'ticket_custom_logo']);
        });
    }
};
