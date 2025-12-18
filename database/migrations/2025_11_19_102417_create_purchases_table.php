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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            
            // User who purchased
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Event being purchased
            $table->foreignId('event_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Ticket details
            $table->string('ticket_type');   // vip, regular, early_bird etc.
            $table->integer('quantity');     // number of tickets
            $table->decimal('total_price', 10, 2); // total price

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
