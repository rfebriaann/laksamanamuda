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
        if (!Schema::hasTable('reservation_seats')) {
            Schema::create('reservation_seats', function (Blueprint $table) {
                $table->id('reservation_seat_id');
                
                // Foreign keys
                $table->unsignedBigInteger('reservation_id');
                $table->unsignedBigInteger('seat_id')->nullable();
                $table->unsignedBigInteger('table_id')->nullable();
                
                // Price at time of reservation
                $table->decimal('seat_price', 10, 2);
                
                // Status for future use
                $table->string('status')->default('active');
                
                $table->timestamps();

                // Indexes
                $table->index('reservation_id');
                $table->index('seat_id');
                $table->index('table_id');
                $table->index(['reservation_id', 'seat_id']);
                $table->index(['reservation_id', 'table_id']);

                // Foreign key constraints
                $table->foreign('reservation_id')
                      ->references('reservation_id')
                      ->on('reservations')
                      ->onDelete('cascade');

                // FIXED: Reference correct primary key untuk tables
                if (Schema::hasTable('tables')) {
                    $table->foreign('table_id')
                          ->references('table_id')  // Sesuaikan dengan primary key di tabel tables
                          ->on('tables')
                          ->onDelete('cascade');
                }

                // FIXED: Reference correct primary key untuk seats (jika ada)
                if (Schema::hasTable('seats')) {
                    $table->foreign('seat_id')
                          ->references('seat_id')   // Sesuaikan dengan primary key di tabel seats
                          ->on('seats')
                          ->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_seats');
    }
};