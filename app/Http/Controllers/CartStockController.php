<?php

namespace App\Http\Controllers;

use App\Models\ProductInventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartStockController extends Controller
{
    public function check(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'slugs' => ['required', 'array', 'min:1', 'max:50'],
            'slugs.*' => [
                'required',
                'string',
                'max:150',
                'distinct',
            ],
        ]);

        $catalog = config('products', []);

        $knownSlugs = array_values(array_filter(
            $validated['slugs'],
            fn ($slug) => array_key_exists($slug, $catalog)
        ));

        $stocks = ProductInventory::query()
            ->whereIn('product_slug', $knownSlugs)
            ->pluck('stock', 'product_slug');

        $products = [];

        foreach ($validated['slugs'] as $slug) {
            $exists = array_key_exists($slug, $catalog);

            $product = $exists ? $catalog[$slug] : null;

            $products[$slug] = [
                'exists' => $exists,
                'available' => $exists
                    ? max(0, (int) ($stocks[$slug] ?? 0))
                    : 0,
                'name' => $product['name'] ?? null,
                'price' => $exists
                    ? (float) $product['price']
                    : null,
                'image' => $product['image'] ?? null,
            ];
        }

        return response()
            ->json(['products' => $products])
            ->header(
                'Cache-Control',
                'no-store, no-cache, must-revalidate'
            );
    }
}