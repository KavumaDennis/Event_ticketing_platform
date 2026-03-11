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
            // Ticket Settings Defaults
            $table->unsignedInteger('default_ticket_price')->default(0);
            $table->unsignedInteger('default_ticket_quantity')->default(0);
            $table->string('default_ticket_type')->default('Regular');

            // Payout Settings
            $table->string('payout_mobile_money_number')->nullable();
            $table->string('payout_bank_name')->nullable();
            $table->string('payout_account_number')->nullable();
            $table->string('payout_account_name')->nullable();

            // Page Access
            $table->text('authorized_emails')->nullable(); // Store as comma/newline separated
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->dropColumn([
                'default_ticket_price',
                'default_ticket_quantity',
                'default_ticket_type',
                'payout_mobile_money_number',
                'payout_bank_name',
                'payout_account_number',
                'payout_account_name',
                'authorized_emails',
            ]);
        });
    }
};
