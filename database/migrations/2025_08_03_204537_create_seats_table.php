<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id('seat_id');
            $table->foreignId('layout_id')->constrained('seat_layouts', 'layout_id')->onDelete('cascade');
            $table->string('seat_number');
            $table->string('seat_row');
            $table->enum('seat_type', ['VIP', 'Regular']);
            $table->decimal('seat_price', 10, 2);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->unique(['layout_id', 'seat_number', 'seat_row']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};