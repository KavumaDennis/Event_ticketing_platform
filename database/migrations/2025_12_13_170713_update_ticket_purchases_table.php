<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ticket_purchases', function (Blueprint $table) {

            // Owner (dashboard)
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();

            // MTN callback identifier
            $table->string('external_id')
                ->nullable()
                ->after('reference_id')
                ->index();

            // Currency support
            $table->string('currency', 5)
                ->default('UGX')
                ->after('total');

            // Payment timestamp
            $table->timestamp('paid_at')
                ->nullable()
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'external_id',
                'currency',
                'paid_at'
            ]);
        });
    }
};
