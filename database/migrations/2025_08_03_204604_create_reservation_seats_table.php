<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_seats', function (Blueprint $table) {
            $table->id('reservation_seat_id');
            $table->foreignId('reservation_id')->constrained('reservations', 'reservation_id')->onDelete('cascade');
            $table->foreignId('seat_id')->constrained('seats', 'seat_id');
            $table->decimal('seat_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_seats');
    }
};