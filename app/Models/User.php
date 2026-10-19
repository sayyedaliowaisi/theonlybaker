<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNABLE ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTE CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER ORDERS
    |--------------------------------------------------------------------------
    */

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER WISHLIST
    |--------------------------------------------------------------------------
    */

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCT REVIEWS
    |--------------------------------------------------------------------------
    */

    public function productReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER SAVED ADDRESSES
    |--------------------------------------------------------------------------
    |
    | One customer can save multiple delivery addresses.
    | Example: Home, Office, Other.
    |
    */

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /*
    |--------------------------------------------------------------------------
    | DEFAULT DELIVERY ADDRESS
    |--------------------------------------------------------------------------
    */

    public function defaultAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class)
            ->where('is_default', true);
    }

    /*
    |--------------------------------------------------------------------------
    | B2B RETAILER PROFILE
    |--------------------------------------------------------------------------
    */

    public function retailerProfile(): HasOne
    {
        return $this->hasOne(RetailerProfile::class);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IF RETAILER ACCOUNT EXISTS
    |--------------------------------------------------------------------------
    */

    public function isRetailer(): bool
    {
        return $this->retailerProfile()->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK APPROVED RETAILER
    |--------------------------------------------------------------------------
    |
    | Only approved retailers qualify for wholesale access.
    |
    */

    public function isApprovedRetailer(): bool
    {
        return $this->retailerProfile()
            ->where('status', 'approved')
            ->exists();
    }
}
