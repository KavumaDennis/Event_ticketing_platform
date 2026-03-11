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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_purchase_id')->constrained('ticket_purchases')->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            $table->string('ticket_number')->unique(); // Unique ticket code
            $table->string('ticket_type');
            $table->integer('quantity')->default(1);

            $table->string('download_url')->nullable(); // URL for PDF download
            $table->string('qr_code_path')->nullable(); // Path to QR code image

            $table->enum('status', ['valid', 'used'])->default('valid');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
