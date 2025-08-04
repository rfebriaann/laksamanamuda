<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_layouts', function (Blueprint $table) {
            $table->id('layout_id');
            $table->foreignId('event_id')->constrained('events', 'event_id')->onDelete('cascade');
            $table->string('layout_name');
            $table->json('layout_config'); // JSON untuk menyimpan konfigurasi layout
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_layouts');
    }
};