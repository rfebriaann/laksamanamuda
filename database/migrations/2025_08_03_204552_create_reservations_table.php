<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id('reservation_id');
                $table->string('reservation_code')->unique();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('event_id');
                $table->enum('reservation_status', ['pending', 'confirmed', 'cancelled', 'expired'])->default('pending');
                $table->decimal('total_amount', 12, 2);
                $table->integer('total_seats');
                $table->timestamp('reservation_date');
                $table->timestamp('expire_date')->nullable();
                $table->timestamps();

                $table->index(['event_id', 'reservation_status']);
                $table->index('user_id');
                $table->index('reservation_code');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};