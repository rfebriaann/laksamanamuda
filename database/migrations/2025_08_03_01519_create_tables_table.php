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
        Schema::create('tables', function (Blueprint $table) {
            $table->id('table_id');
            $table->foreignId('layout_id')->constrained('seat_layouts', 'layout_id')->onDelete('cascade');
            $table->string('table_number');
            $table->integer('capacity');
            $table->enum('table_type', ['Regular', 'Gold', 'VIP'])->default('Regular');
            $table->decimal('table_price', 10, 2);
            $table->float('position_x');
            $table->float('position_y');
            $table->float('width')->default(120);
            $table->float('height')->default(120);
            $table->string('shape')->default('square'); // square, circle, rectangle, diamond
            $table->boolean('is_available')->default(true);
            $table->json('table_metadata')->nullable();
            $table->timestamps();

            $table->index(['layout_id', 'is_available']);
            $table->index(['layout_id', 'shape']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
