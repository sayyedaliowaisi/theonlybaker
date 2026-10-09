<?php

namespace Database\Seeders;

use App\Models\ProductInventory;
use Illuminate\Database\Seeder;

class ProductInventorySeeder extends Seeder
{
    public function run(): void
    {
        $products = config('products', []);


        foreach ($products as $slug => $product) {

            ProductInventory::firstOrCreate(
                [
                    'product_slug' => $slug,
                ],
                [
                    'stock' => 0,
                    'low_stock_threshold' => 5,
                ]
            );
        }
    }
}