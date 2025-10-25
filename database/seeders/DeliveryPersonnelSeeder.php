<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class DeliveryPersonnelSeeder extends Seeder
{
    public function run(): void
    {
        $deliveryPersonnel = [
            [
                'name' => 'Delivery Person 1',
                'email' => 'delivery1@gmail.com',
                'user_type' => 'Delivery',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Delivery Person 2',
                'email' => 'delivery2@gmail.com',
                'user_type' => 'Delivery',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Delivery Person 3',
                'email' => 'delivery3@gmail.com',
                'user_type' => 'Delivery',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($deliveryPersonnel as $person) {
            Admin::updateOrCreate(
                ['email' => $person['email']],
                $person
            );
        }
    }
}
