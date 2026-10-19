<?php

namespace App\Http\Controllers;

use App\Models\ProductInventory;
use App\Services\ProductCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartStockController extends Controller
{
    public function check(
        Request $request,
        ProductCatalog $catalog
    ): JsonResponse {
        $validated = $request->validate([
            'slugs' => [
                'required',
                'array',
                'min:1',
                'max:50',
            ],

            'slugs.*' => [
                'required',
                'string',
                'max:150',
                'distinct',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | GET ACTIVE PRODUCTS
        |--------------------------------------------------------------------------
        */

        $productsBySlug = $catalog->findMany(
            $validated['slugs']
        );

        /*
        |--------------------------------------------------------------------------
        | GET INVENTORY
        |--------------------------------------------------------------------------
        */

        $stocks = ProductInventory::query()
            ->whereIn(
                'product_slug',
                array_keys($productsBySlug)
            )
            ->pluck('stock', 'product_slug');

        /*
        |--------------------------------------------------------------------------
        | CHECK RETAILER APPROVAL
        |--------------------------------------------------------------------------
        */

        
        $user = auth('web')->user();

        $isApprovedRetailer = $user instanceof \App\Models\User
            && $user->isApprovedRetailer();


        /*
        |--------------------------------------------------------------------------
        | PREPARE RESPONSE
        |--------------------------------------------------------------------------
        */

        $products = [];

        foreach ($validated['slugs'] as $slug) {
            $product = $productsBySlug[$slug] ?? null;

            $exists = $product !== null;

            $available = $exists
                ? max(0, (int) ($stocks[$slug] ?? 0))
                : 0;

            $retailPrice = $exists
                ? (float) $product['price']
                : null;

            $hasWholesalePricing = $exists
                && $isApprovedRetailer
                && !empty($product['has_wholesale_pricing']);

            $wholesalePrice = $hasWholesalePricing
                ? (float) $product['wholesale_price']
                : null;

            $wholesaleMoq = $hasWholesalePricing
                ? max(1, (int) $product['wholesale_moq'])
                : null;

            /*
|--------------------------------------------------------------------------
| RETAIL AND WHOLESALE PRICING
|--------------------------------------------------------------------------
|
| Keep the retail price in the existing price field.
| Eligible retailers receive wholesale pricing separately.
| Cart and checkout calculate their displayed totals using
| verified pricing, while the server determines the final price.
|
*/

            $products[$slug] = [
                'exists' => $exists,

                'available' => $available,

                'name' => $product['name'] ?? null,

                'price' => $retailPrice,

                'image' => $exists && !empty($product['image'])
                    ? asset($product['image'])
                    : null,

                /*
                |--------------------------------------------------------------------------
                | B2B WHOLESALE INFORMATION
                |--------------------------------------------------------------------------
                */

                'has_wholesale_pricing' => $hasWholesalePricing,

                'wholesale_price' => $wholesalePrice,

                'wholesale_moq' => $wholesaleMoq,

                
                /*
                |--------------------------------------------------------------------------
                | WHOLESALE CHECKOUT AVAILABILITY
                |--------------------------------------------------------------------------
                */

                'wholesale_checkout_enabled' => $hasWholesalePricing
                    && !empty($product['wholesale_checkout_enabled']),

            ];
        }

        return response()
            ->json([
                'products' => $products,
            ])
            ->header(
                'Cache-Control',
                'no-store, no-cache, must-revalidate'
            );
    }
}
