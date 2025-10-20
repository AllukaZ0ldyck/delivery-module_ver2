<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Product 1', 'price' => 30.00, 'stock' => 250],
            ['name' => 'Product 2', 'price' => 35.00, 'stock' => 800],
            ['name' => 'Product 3', 'price' => 40.00, 'stock' => 600],
            ['name' => 'Product 4', 'price' => 45.00, 'stock' => 500],
            ['name' => 'Product 5', 'price' => 50.00, 'stock' => 300],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'price' => $product['price'],
                    'stock' => $product['stock']
                ]
            );
        }
    }
}
