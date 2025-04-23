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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('seat_code');
            $table->enum('seat_type', ['normal', 'vip', 'sweetbox'])->default('normal');
            $table->boolean('is_sweetbox')->default(false);
        });

        Schema::create('seat_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cinema_id')->constrained()->onDelete('cascade');
            $table->enum('seat_type', ['normal', 'vip', 'sweetbox'])->default('normal');
            $table->enum('day_type', ['weekday', 'weekend', 'holiday'])->default('weekday');
            $table->decimal('price', 10, 2);

            // Composite unique key
            $table->unique(['cinema_id', 'seat_type', 'day_type']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
        Schema::dropIfExists('seat_prices');
    }
};
