<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->decimal('total_base', 10, 2)->nullable()->after('service_fee');
            $table->string('base_currency', 5)->default('UGX')->after('total_base');
            $table->decimal('fx_rate', 12, 6)->nullable()->after('base_currency');
            $table->string('fx_source')->nullable()->after('fx_rate');
            $table->timestamp('fx_at')->nullable()->after('fx_source');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->dropColumn(['total_base', 'base_currency', 'fx_rate', 'fx_source', 'fx_at']);
        });
    }
};
