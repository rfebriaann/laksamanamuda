<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('reservation_id');
            $table->string('reservation_code')->unique();
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('event_id')->constrained('events', 'event_id');
            $table->enum('reservation_status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->integer('total_seats');
            $table->timestamp('reservation_date');
            $table->timestamp('expire_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};