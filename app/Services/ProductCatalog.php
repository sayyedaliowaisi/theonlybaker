<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class ProductCatalog
{
    public function all(bool $activeOnly = true): array
    {
        $query = Product::query()
            ->orderBy('sort_order')
            ->orderBy('id');

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->get()
            ->mapWithKeys(fn (Product $product) => [
                $product->slug => $this->toArray($product),
            ])
            ->all();
    }

    public function find(string $slug): ?array
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        return $product
            ? $this->toArray($product)
            : null;
    }

    public function findMany(array $slugs): array
    {
        return Product::query()
            ->whereIn('slug', $slugs)
            ->where('is_active', true)
            ->get()
            ->mapWithKeys(fn (Product $product) => [
                $product->slug => $this->toArray($product),
            ])
            ->all();
    }

    public function toArray(Product $product): array
    {
        $image = $product->image;

        if ($image && str_starts_with($image, 'products/')) {
            $image = 'storage/' . $image;
        }

        /*
        |--------------------------------------------------------------------------
        | Retailer Authentication
        |--------------------------------------------------------------------------
        | Use the customer web guard explicitly.
        | Admin accounts must not be treated as retailers.
        */

        $customer = auth('web')->user();

        $approvedRetailer = $customer instanceof User
            && $customer->isApprovedRetailer();

        /*
        |--------------------------------------------------------------------------
        | Wholesale Eligibility
        |--------------------------------------------------------------------------
        */

        $wholesaleConfigured = $product->hasWholesalePricing();

        $wholesaleEligible = $approvedRetailer
            && $wholesaleConfigured;

        /*
        |--------------------------------------------------------------------------
        | Product Data
        |--------------------------------------------------------------------------
        */

        return [
            // Existing product fields
            'slug' => $product->slug,
            'number' => $product->number,
            'name' => $product->name,
            'description' => $product->description,
            'price' => (float) $product->price,
            'image' => $image,
            'category' => $product->category,
            'is_featured' => (bool) $product->is_featured,

            // Wholesale information visible only
            // to approved retailers
            'wholesale_price' => $wholesaleEligible
                ? (float) $product->wholesale_price
                : null,

            'wholesale_moq' => $wholesaleEligible
                ? $product->getWholesaleMinimumQuantity()
                : null,

            'has_wholesale_pricing' => $wholesaleEligible,

            
            // Enable wholesale checkout only for approved
            // retailers when this product has wholesale pricing.
            'wholesale_checkout_enabled' => $wholesaleEligible,

        ];
    }
}
