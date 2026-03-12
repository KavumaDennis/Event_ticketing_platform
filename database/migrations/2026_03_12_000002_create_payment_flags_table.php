<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_purchase_id')->constrained('ticket_purchases')->onDelete('cascade');
            $table->enum('type', ['fraud', 'chargeback']);
            $table->enum('status', ['open', 'reviewed', 'cleared'])->default('open');
            $table->string('source')->default('system');
            $table->text('reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('flagged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_flags');
    }
};
