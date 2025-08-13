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
        Schema::create('seat_layouts', function (Blueprint $table) {
            $table->id('layout_id');
            $table->foreignId('event_id')->constrained('events', 'event_id')->onDelete('cascade');
            $table->string('selling_mode')->default('per_seat');
            $table->string('layout_name');
            $table->json('layout_config'); // stores rows, columns, vip_rows, pricing, etc.
            $table->string('background_image')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index('event_id');
            $table->index(['event_id', 'layout_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_layouts');
    }
};