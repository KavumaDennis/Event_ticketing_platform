<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trends', function (Blueprint $table) {

            // Prevent duplicate column error
            if (!Schema::hasColumn('trends', 'event_id')) {

                $table->foreignId('event_id')
                    ->nullable()
                    ->constrained('events')
                    ->nullOnDelete()
                    ->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trends', function (Blueprint $table) {

            // Only drop the foreign key/column if they exist
            if (Schema::hasColumn('trends', 'event_id')) {

                // Drop FK if it exists
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $fkNames = array_keys($sm->listTableForeignKeys('trends'));

                foreach ($fkNames as $fkName) {
                    if (str_contains($fkName, 'event_id')) {
                        $table->dropForeign($fkName);
                    }
                }

                $table->dropColumn('event_id');
            }
        });
    }
};
