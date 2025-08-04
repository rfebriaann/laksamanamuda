<?php

namespace Database\Seeders;

use App\Models\Voucher;
use App\Models\User;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('user_type', 'admin')->first();

        // Point redemption voucher
        Voucher::create([
            'voucher_code' => 'POINTS50K',
            'voucher_name' => 'Diskon 50rb dengan Points',
            'voucher_description' => 'Dapatkan diskon Rp 50.000 dengan menukar 500 points',
            'voucher_type' => 'point_redemption',
            'required_points' => 500,
            'discount_amount' => 50000,
            'discount_type' => 'fixed',
            'valid_from' => now(),
            'valid_until' => now()->addMonths(3),
            'max_usage' => null,
            'current_usage' => 0,
            'is_active' => true,
            'created_by' => $admin->user_id,
        ]);

        // Free claim voucher
        Voucher::create([
            'voucher_code' => 'WELCOME25',
            'voucher_name' => 'Welcome Discount 25%',
            'voucher_description' => 'Diskon 25% untuk member baru',
            'voucher_type' => 'free_claim',
            'required_points' => null,
            'discount_amount' => 25,
            'discount_type' => 'percentage',
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'max_usage' => 100,
            'current_usage' => 0,
            'is_active' => true,
            'created_by' => $admin->user_id,
        ]);
    }
}