<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Membership;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'member']);

        // Create SuperAdmin
        $superadmin = User::create([
            'email' => 'superadmin@laksamanamuda.com',
            'password' => Hash::make('password'),
            'full_name' => 'Super Administrator',
            'phone_number' => '081234567890',
            'user_type' => 'superadmin',
            'is_active' => true,
        ]);
        $superadmin->assignRole('superadmin');

        // Create Admin
        $admin = User::create([
            'email' => 'admin@laksamanamuda.com',
            'password' => Hash::make('password'),
            'full_name' => 'Administrator',
            'phone_number' => '081234567891',
            'user_type' => 'admin',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create Sample Members
        for ($i = 1; $i <= 5; $i++) {
            $member = User::create([
                'email' => "member{$i}@example.com",
                'password' => Hash::make('password'),
                'full_name' => "Member {$i}",
                'phone_number' => '08123456789' . $i,
                'user_type' => 'member',
                'is_active' => true,
            ]);

            // Create membership for each member
            Membership::create([
                'user_id' => $member->user_id,
                'membership_number' => 'LM' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'total_points' => rand(100, 1000),
                'joined_at' => now()->subDays(rand(1, 30)),
                'is_active' => true,
            ]);
            $admin->assignRole('member');
        }
    }
}
