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
        Schema::create("bookings", function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->foreignId("showtime_id")->constrained()->onDelete("cascade");
            $table->decimal("total_price", 10, 2);
            $table->enum("payment_status", ["unpaid", "paid", "failed"])->default("unpaid");
            $table->enum("payment_method", [
                "cash",
                "credit_card",
                "bank_transfer",
                "e_wallet"
            ])->default("cash");
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
