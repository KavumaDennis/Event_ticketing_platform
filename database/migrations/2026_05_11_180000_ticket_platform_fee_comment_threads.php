<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ticket_purchases') && ! Schema::hasColumn('ticket_purchases', 'platform_fee_percent')) {
            Schema::table('ticket_purchases', function (Blueprint $table) {
                $table->decimal('platform_fee_percent', 8, 4)->nullable()->after('service_fee');
            });
        }

        if (Schema::hasTable('event_comments') && ! Schema::hasColumn('event_comments', 'parent_id')) {
            Schema::table('event_comments', function (Blueprint $table) {
                $table->foreignId('parent_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('event_comments')
                    ->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('trend_comments') && ! Schema::hasColumn('trend_comments', 'parent_id')) {
            Schema::table('trend_comments', function (Blueprint $table) {
                $table->foreignId('parent_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('trend_comments')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('trend_comments') && Schema::hasColumn('trend_comments', 'parent_id')) {
            Schema::table('trend_comments', function (Blueprint $table) {
                $table->dropConstrainedForeignId('parent_id');
            });
        }

        if (Schema::hasTable('event_comments') && Schema::hasColumn('event_comments', 'parent_id')) {
            Schema::table('event_comments', function (Blueprint $table) {
                $table->dropConstrainedForeignId('parent_id');
            });
        }

        if (Schema::hasTable('ticket_purchases') && Schema::hasColumn('ticket_purchases', 'platform_fee_percent')) {
            Schema::table('ticket_purchases', function (Blueprint $table) {
                $table->dropColumn('platform_fee_percent');
            });
        }
    }
};
