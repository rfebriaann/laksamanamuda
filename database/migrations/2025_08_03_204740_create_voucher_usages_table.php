<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_usage', function (Blueprint $table) {
            $table->id('usage_id');
            $table->foreignId('claim_id')->constrained('voucher_claims', 'claim_id');
            $table->foreignId('reservation_id')->constrained('reservations', 'reservation_id');
            $table->decimal('discount_applied', 10, 2);
            $table->timestamp('used_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_usage');
    }
};
