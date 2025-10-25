<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'user_type' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'staff User',
                'email' => 'staff@gmail.com',
                'user_type' => 'staff',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'delivery@gmail.com'],
            [
                'name' => 'Delivery Man',
                'email' => 'delivery@gmail.com',
                'user_type' => 'delivery',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
