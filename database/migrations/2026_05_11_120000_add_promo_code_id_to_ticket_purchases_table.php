<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->foreignId('promo_code_id')
                ->nullable()
                ->after('event_id')
                ->constrained('promo_codes')
                ->nullOnDelete();
            $table->boolean('promo_redeemed')->default(false)->after('promo_code_id');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->dropForeign(['promo_code_id']);
            $table->dropColumn(['promo_code_id', 'promo_redeemed']);
        });
    }
};
