<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id('point_transaction_id');
            $table->foreignId('membership_id')->constrained('memberships', 'membership_id');
            $table->foreignId('reservation_id')->nullable()->constrained('reservations', 'reservation_id');
            $table->enum('transaction_type', ['earned', 'redeemed']);
            $table->integer('points_amount');
            $table->string('description');
            $table->timestamp('transaction_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};