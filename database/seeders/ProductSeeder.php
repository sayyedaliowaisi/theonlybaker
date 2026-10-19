<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = config('products', []);

        foreach ($products as $slug => $data) {
            Product::query()->firstOrCreate(
                [
                    'slug' => $slug,
                ],
                [
                    'number' => $data['number'] ?? null,
                    'name' => $data['name'],
                    'description' => null,
                    'price' => $data['price'],
                    'image' => $data['image'] ?? null,
                    'category' => $data['category'],
                    'is_active' => true,
                    'is_featured' => false,
                    'sort_order' => $data['number'] ?? 0,
                ]
            );
        }
    }
}
