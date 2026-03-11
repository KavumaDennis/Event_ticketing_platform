<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('organizer');
            $table->string('location');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('description')->nullable();
            $table->string('event_image')->nullable();

            //Ticket Details
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->integer('regular_quantity')->nullable();

            $table->decimal('vip_price', 10, 2)->nullable();
            $table->integer('vip_quantity')->nullable();

            $table->decimal('vvip_price', 10, 2)->nullable();
            $table->integer('vvip_quantity')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
