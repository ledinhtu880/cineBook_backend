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
        Schema::create("bookings", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->foreignId("showtime_id")->constrained()->onDelete("cascade");
            $table->foreignId("product_id")->nullable()->constrained()->onDelete("set null");
            $table->foreignId("product_combo_id")->nullable()->constrained()->onDelete("set null");
            $table->integer("product_quantity")->nullable();
            $table->decimal("total_price", 10, 2);
            $table->enum("payment_status", ["unpaid", "paid"])->default("unpaid");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
