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
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id'); // Custom primary key name
            $table->string('event_name');
            $table->text('event_description')->nullable();
            $table->string('venue_name');
            $table->text('venue_address');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('event_image')->nullable();
            
            // FIX: Explicitly reference users.user_id
            $table->foreignId('created_by')->constrained('users', 'id')->onDelete('cascade');
            
            $table->timestamps();

            // Add indexes for better performance
            $table->index('event_date');
            $table->index('created_by');
            $table->index(['event_name', 'event_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};