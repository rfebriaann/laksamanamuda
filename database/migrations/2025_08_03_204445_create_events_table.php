<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('event_name');
            $table->text('event_description')->nullable();
            $table->string('venue_name');
            $table->text('venue_address');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('event_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users', 'user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};