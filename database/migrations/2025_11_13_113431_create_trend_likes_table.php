<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trend_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trend_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['trend_id', 'user_id']); // each user can like a trend once
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trend_likes');
    }
};
