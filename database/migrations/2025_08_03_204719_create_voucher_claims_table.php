<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_claims', function (Blueprint $table) {
            $table->id('claim_id');
            $table->foreignId('voucher_id')->constrained('vouchers', 'voucher_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('membership_id')->nullable()->constrained('memberships', 'membership_id');
            $table->enum('claim_status', ['active', 'used', 'expired'])->default('active');
            $table->timestamp('claimed_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_claims');
    }
};