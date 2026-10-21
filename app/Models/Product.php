<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

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
        'banner_image',

        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_image',
        'seo_noindex',

        'subtitle',
        'premium_badge_text',
        'show_premium_badge',
        'ingredients',
        'nutrition_information',
        'storage_instructions',
        'shipping_delivery',

        'benefit_1_icon',
        'benefit_1_title',
        'benefit_1_description',

        'benefit_2_icon',
        'benefit_2_title',
        'benefit_2_description',

        'benefit_3_icon',
        'benefit_3_title',
        'benefit_3_description',

        'benefit_4_icon',
        'benefit_4_title',
        'benefit_4_description',
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
            'show_premium_badge' => 'boolean',
            'seo_noindex' => 'boolean',
        ];
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(
            ProductInventory::class,
            'product_slug',
            'slug'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->inventory !== null
            && $this->inventory->stock > 0;
    }

    public function hasWholesalePricing(): bool
    {
        return $this->wholesale_price !== null
            && (float) $this->wholesale_price > 0;
    }

    public function getWholesaleMinimumQuantity(): int
    {
        return max(1, (int) $this->wholesale_moq);
    }

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

    public function galleryImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}