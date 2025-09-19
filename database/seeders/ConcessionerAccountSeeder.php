<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ConcessionerAccount;

class ConcessionerAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            ConcessionerAccount::create([
                'user_id' => $user->id,
                'zone' => 'Zone ' . chr(rand(65, 67)),
                'account_no' => 'ACCT' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'address' => fake()->address(),
                'property_type' => 'Residential',
                "rate_code" =>rand(1, 5),
                'status' => 'Active',
                'meter_brand' => 'Brand ' . rand(1, 3),
                'meter_serial_no' => strtoupper(fake()->bothify('SN####')),
                'sc_no' => 'SC-' . rand(1000, 9999),
                'date_connected' => now()->subDays(rand(10, 300)),
                'sequence_no' => rand(1, 100),
                'meter_type' => 'Type-' . rand(1, 3),
                'meter_wire' => 'Wire-' . rand(1, 3),
                'meter_form' => 'Form-' . rand(1, 3),
                'meter_class' => 'Class-' . rand(1, 3),
                'lat_long' => fake()->latitude() . ', ' . fake()->longitude(),
                'isErcSealed' => (bool)rand(0, 1),
                'inspection_image' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
