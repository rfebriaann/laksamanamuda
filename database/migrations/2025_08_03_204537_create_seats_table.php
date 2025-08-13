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
        Schema::create('seats', function (Blueprint $table) {
            $table->id('seat_id');
            $table->foreignId('layout_id')->constrained('seat_layouts', 'layout_id')->onDelete('cascade');
            // $table->foreignId('table_id')->nullable()->constrained('tables', 'table_id')->nullOnDelete();
            $table->string('seat_number');
            $table->string('seat_row'); // A, B, C, etc.
            $table->enum('seat_type', ['Regular', 'Gold', 'VIP'])->default('Regular');
            $table->decimal('seat_price', 10, 2);
            $table->boolean('is_available')->default(true);
            
            // New columns for interactive layout positions
            $table->integer('position_x')->nullable()->default(0);
            $table->integer('position_y')->nullable()->default(0);

            if (!Schema::hasColumn('seats', 'width')) {
                $table->integer('width')->default(44);
            }

            if (!Schema::hasColumn('seats', 'height')) {
                $table->integer('height')->default(44);
            }
            
            // Additional metadata for custom layouts
            if (!Schema::hasColumn('seats', 'seat_metadata')) {
                $table->json('seat_metadata')->nullable();
            }
            
            $table->timestamps();

            // Add indexes for better performance
            // $table->index('layout_id');
            // $table->index(['layout_id', 'seat_row']);
            // $table->index(['layout_id', 'seat_type']);
            // $table->index(['layout_id', 'is_available']);
            // $table->index(['layout_id', 'position_x', 'position_y']);
            $table->index(['layout_id', 'position_x', 'position_y'], 'seats_position_idx');
            $table->index(['layout_id', 'seat_type', 'is_available'], 'seats_type_availability_idx');
            
            // Unique constraint to prevent duplicate seats in same layout
            $table->unique(['layout_id', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};