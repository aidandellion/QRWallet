<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        DB::table('roles')->insert([
            ['role_name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Default admin account
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@qrwallet.com',
            'password' => Hash::make('admin123'),
            'role_id' => 1,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Payment platforms
        DB::table('payment_platforms')->insert([
            ['platform_name' => 'Maybank', 'created_at' => now(), 'updated_at' => now()],
            ['platform_name' => 'CIMB', 'created_at' => now(), 'updated_at' => now()],
            ['platform_name' => 'Public Bank', 'created_at' => now(), 'updated_at' => now()],
            ['platform_name' => 'RHB', 'created_at' => now(), 'updated_at' => now()],
            ['platform_name' => 'Bank Islam', 'created_at' => now(), 'updated_at' => now()],
            ['platform_name' => 'Touch \'n Go', 'created_at' => now(), 'updated_at' => now()],
            ['platform_name' => 'ShopeePay', 'created_at' => now(), 'updated_at' => now()],
            ['platform_name' => 'MAE', 'created_at' => now(), 'updated_at' => now()],
            ['platform_name' => 'Boost', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->call([
            DummyUsersSeeder::class,
        ]);
    }
}