<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'checkout_token',
        'name',
        'email',
        'phone',
        'address',
        'landmark',
        'city',
        'state',
        'pincode',
        'country',
        'payment_method',
        'payment_status',
        'order_status',
        'subtotal',
        'shipping',
        'total',
        'inventory_deducted_at',
        'inventory_restored_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping' => 'decimal:2',
            'total' => 'decimal:2',
            'inventory_deducted_at' => 'datetime',
            'inventory_restored_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}