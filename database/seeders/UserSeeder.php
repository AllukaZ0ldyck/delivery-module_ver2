<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ Create a sample approved customer
        User::create([
            'firstname'        => 'Sample',
            'lastname'         => 'User',
            'name'             => 'Sample User',
            'email'            => 'sample1@gmail.com',
            'contact'          => '09123456789',
            'address'          => '123 Main Street, Tiaong, Quezon',
            'gallon_type'      => 'Blue 5 Gallon',
            'gallon_count'     => 2,
            'role'             => 'customer',
            'approval_status'  => 'approved',
            'qr_token'         => Str::uuid(),
            'confirmation_code'=> strtoupper(Str::random(10)),
            'email_verified_at'=> now(),
            'password'         => Hash::make('password'),
            'remember_token'   => Str::random(10),
        ]);

        // ✅ Generate 25 random approved customers
        User::factory()->count(15)->create([
            'role'        => 'customer',
            'approval_status'  => 'pending',
            'qr_token'         => Str::uuid(),
            'confirmation_code'=> strtoupper(Str::random(10)),
            'email_verified_at'=> now(),
        ]);
        User::factory()->count(5)->create([
            'role'        => 'customer',
            'approval_status'  => 'approved',
            'qr_token'         => Str::uuid(),
            'confirmation_code'=> strtoupper(Str::random(10)),
            'email_verified_at'=> now(),
        ]);
    }
}
