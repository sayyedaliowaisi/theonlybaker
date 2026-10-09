<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductInventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductInventoryController extends Controller
{
    public function index(Request $request): View
    {
        $products = config('products', []);


        /*
        |--------------------------------------------------------------------------
        | ENSURE EVERY CONFIG PRODUCT HAS INVENTORY
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | INVENTORY RECORDS
        |--------------------------------------------------------------------------
        */

        $inventories = ProductInventory::query()
            ->get()
            ->keyBy('product_slug');


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->query('search', '')
        );


        if ($search !== '') {

            $products = array_filter(
                $products,
                function ($product, $slug) use ($search) {

                    return str_contains(
                        strtolower($product['name'] ?? ''),
                        strtolower($search)
                    )
                    ||
                    str_contains(
                        strtolower($slug),
                        strtolower($search)
                    );
                },
                ARRAY_FILTER_USE_BOTH
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */

        $stats = [
            'products' => count(config('products', [])),

            'in_stock' => $inventories
                ->filter(
                    fn ($inventory) =>
                        $inventory->stock >
                        $inventory->low_stock_threshold
                )
                ->count(),

            'low_stock' => $inventories
                ->filter(
                    fn ($inventory) =>
                        $inventory->stock > 0 &&
                        $inventory->stock <=
                        $inventory->low_stock_threshold
                )
                ->count(),

            'out_of_stock' => $inventories
                ->where('stock', 0)
                ->count(),
        ];


        return view(
            'admin.inventory.index',
            compact(
                'products',
                'inventories',
                'stats',
                'search'
            )
        );
    }


    public function update(
        Request $request,
        string $slug
    ): RedirectResponse {

        $products = config('products', []);


        abort_unless(
            isset($products[$slug]),
            404
        );


        $validated = $request->validate([
            'stock' => [
                'required',
                'integer',
                'min:0',
                'max:1000000',
            ],

            'low_stock_threshold' => [
                'required',
                'integer',
                'min:0',
                'max:1000000',
            ],
        ]);


        ProductInventory::updateOrCreate(
            [
                'product_slug' => $slug,
            ],
            [
                'stock' =>
                    $validated['stock'],

                'low_stock_threshold' =>
                    $validated['low_stock_threshold'],
            ]
        );


        return back()->with(
            'success',
            $products[$slug]['name'] .
            ' inventory updated successfully.'
        );
    }
}