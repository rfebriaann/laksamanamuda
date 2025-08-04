<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id('voucher_id');
            $table->string('voucher_code')->unique();
            $table->string('voucher_name');
            $table->text('voucher_description')->nullable();
            $table->enum('voucher_type', ['point_redemption', 'free_claim']);
            $table->integer('required_points')->nullable();
            $table->decimal('discount_amount', 10, 2);
            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->date('valid_from');
            $table->date('valid_until');
            $table->integer('max_usage')->nullable();
            $table->integer('current_usage')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users', 'user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};