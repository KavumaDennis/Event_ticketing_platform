<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('reference_id')->unique(); // MTN reference / external ID
            $table->string('ticket_type');             // regular, vip, vvip
            $table->integer('quantity');

            $table->unsignedBigInteger('amount');      // total amount paid
            $table->string('currency')->default('UGX');

            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])
                ->default('pending');

            $table->string('phone')->nullable();        // MoMo number
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
