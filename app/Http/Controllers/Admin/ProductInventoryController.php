<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Services\ProductCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductInventoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INVENTORY LIST
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        ProductCatalog $catalog
    ): View {

        // Include active and inactive products in admin inventory.
        $products = $catalog->all(false);

        /*
        |--------------------------------------------------------------------------
        | ENSURE EVERY DATABASE PRODUCT HAS INVENTORY
        |--------------------------------------------------------------------------
        */

        foreach ($products as $slug => $product) {

            ProductInventory::query()->firstOrCreate(
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
            ->whereIn(
                'product_slug',
                array_keys($products)
            )
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
            'products' => count($catalog->all(false)),

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
                ->filter(
                    fn ($inventory) =>
                        $inventory->stock <= 0
                )
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

    /*
    |--------------------------------------------------------------------------
    | UPDATE INVENTORY
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $slug
    ): RedirectResponse {

        // Inactive products can still have inventory managed.
        $product = Product::query()
            ->where('slug', $slug)
            ->firstOrFail();

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

        DB::transaction(function () use ($slug, $validated) {

            $inventory = ProductInventory::query()
                ->where('product_slug', $slug)
                ->lockForUpdate()
                ->first();

            if (!$inventory) {
                $inventory = ProductInventory::query()
                    ->create([
                        'product_slug' => $slug,
                        'stock' => 0,
                        'low_stock_threshold' => 5,
                    ]);
            }

            $inventory->update([
                'stock' => $validated['stock'],
                'low_stock_threshold' =>
                    $validated['low_stock_threshold'],
            ]);
        });

        return back()->with(
            'success',
            $product->name .
            ' inventory updated successfully.'
        );
    }
}
