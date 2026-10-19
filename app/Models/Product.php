<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'slug',
        'number',
        'name',
        'description',
        'price',
        'wholesale_price',
        'wholesale_moq',
        'image',
        'category',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'number' => 'integer',
            'price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
            'wholesale_moq' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Product inventory relationship.
     */
    public function inventory(): HasOne
    {
        return $this->hasOne(
            ProductInventory::class,
            'product_slug',
            'slug'
        );
    }

    /**
     * Only active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check whether product is in stock.
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->inventory !== null
            && $this->inventory->stock > 0;
    }

    /**
     * Check whether wholesale pricing is configured.
     */
    public function hasWholesalePricing(): bool
    {
        return $this->wholesale_price !== null
            && (float) $this->wholesale_price > 0;
    }

    /**
     * Minimum quantity required for wholesale orders.
     */
    public function getWholesaleMinimumQuantity(): int
    {
        return max(1, (int) $this->wholesale_moq);
    }

    /**
     * Return price according to approved retailer status.
     *
     * Note: Checkout must independently validate
     * wholesale eligibility and minimum quantity.
     */
    public function getPriceForUser(?User $user): string
    {
        if (
            $user !== null
            && $user->isApprovedRetailer()
            && $this->hasWholesalePricing()
        ) {
            return (string) $this->wholesale_price;
        }

        return (string) $this->price;
    }
}
