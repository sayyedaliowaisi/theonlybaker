<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    protected $fillable = [
        'user_id',
        'product_slug',
        'rating',
        'review',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check whether this review belongs to a customer
     * who has a delivered order containing this product.
     */
    public function isVerifiedPurchase(): bool
    {
        if (!$this->user_id || !$this->product_slug) {
            return false;
        }

        return Order::query()
            ->where('user_id', $this->user_id)
            ->where('order_status', 'delivered')
            ->whereHas('items', function ($query) {
                $query->where(
                    'product_slug',
                    $this->product_slug
                );
            })
            ->exists();
    }
}