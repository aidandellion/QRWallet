<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyUsersSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Ahmad Faiz', 'Nurul Aina', 'Muhammad Hafiz', 'Siti Aisyah', 'Amirul Hakim',
            'Farah Diana', 'Zulkifli Rahman', 'Nur Syahirah', 'Khairul Anuar', 'Aina Sofea',
            'Mohd Danish', 'Wan Nurul Izzah', 'Haziq Iman', 'Nabila Yasmin', 'Aiman Zulkarnain',
            'Fatimah Zahra', 'Iskandar Zulkarnain', 'Alya Batrisyia', 'Rizal Hakimi', 'Nadia Qistina',
        ];

        foreach ($names as $index => $name) {
            $email = strtolower(str_replace(' ', '.', $name)) . '@example.com';

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role_id' => 2, // Account Holder
                'status' => fake()->randomElement(['active', 'inactive']),
            ]);
        }
    }
}