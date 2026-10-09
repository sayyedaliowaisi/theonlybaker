<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    protected $fillable = [
        'product_slug',
        'stock',
        'low_stock_threshold',
    ];


    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'low_stock_threshold' => 'integer',
        ];
    }


    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }


    public function getIsLowStockAttribute(): bool
    {
        return $this->stock > 0
            && $this->stock <= $this->low_stock_threshold;
    }
}