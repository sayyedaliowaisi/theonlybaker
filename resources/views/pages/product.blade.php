
@extends('layouts.app')

@section('title', $product['name'] . ' | The Only Baker')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reviews.css') }}">
@endpush

@php
    /*
    |--------------------------------------------------------------------------
    | PRODUCT INVENTORY
    |--------------------------------------------------------------------------
    */
    $productStock = max(0, (int) ($stock ?? 0));
    $productInStock = $productStock > 0;
    
    $maximumQuantity = $productInStock
        ? min(50, $productStock)
        : 1;

    $wholesaleEnabled =
        !empty($product['has_wholesale_pricing']) &&
        !empty($product['wholesale_checkout_enabled']) &&
        isset($product['wholesale_price'], $product['wholesale_moq']);

    $wholesaleMoq = $wholesaleEnabled
        ? max(1, (int) $product['wholesale_moq'])
        : 1;

    $minimumQuantity = $wholesaleEnabled
        ? $wholesaleMoq
        : 1;

    $canPurchase = $productInStock &&
        $minimumQuantity <= $maximumQuantity;


    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */
    $categoryNames = [
        'dry-fruit' => 'Dry Fruit Cookies',
        'chocolate' => 'Chocolate Cookies',
        'classic' => 'Classic Cookies',
    ];

    $categoryName = $categoryNames[$product['category'] ?? '']
        ?? ucwords(str_replace('-', ' ', $product['category'] ?? 'Cookies'));

    /*
    |--------------------------------------------------------------------------
    | PRODUCT WISHLIST
    |--------------------------------------------------------------------------
    */
    $productInWishlist = auth()->check()
        ? auth()->user()
            ->wishlists()
            ->where('product_slug', $product['slug'])
            ->exists()
        : false;

    /*
    |--------------------------------------------------------------------------
    | RELATED PRODUCTS
    |--------------------------------------------------------------------------
    */
    $relatedProducts = $relatedProducts
        ?? collect(app(\App\Services\ProductCatalog::class)->all())
            ->filter(fn ($item) =>
                $item['slug'] !== $product['slug']
                && $item['category'] === $product['category']
            )
            ->take(4)
            ->values()
            ->all();

    /*
    |--------------------------------------------------------------------------
    | PRODUCT IMAGE
    |--------------------------------------------------------------------------
    */
    $productImage = !empty($product['image'])
        ? asset($product['image'])
        : asset('images/products/cherry-fruit.jpg');

    /*
    |--------------------------------------------------------------------------
    | GALLERY
    |--------------------------------------------------------------------------
    */
    $galleryImages = [];

    if ($product['slug'] === 'gem-badam-pista') {
        for ($i = 1; $i <= 6; $i++) {
            $galleryImages[] = asset(
                'images/product-detail/jem-badam-pista-' . $i . '.jpg'
            );
        }
    } else {
        $galleryImages[] = $productImage;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATED PRODUCT WISHLIST
    |--------------------------------------------------------------------------
    */
    $relatedWishlistSlugs = auth()->check()
        ? auth()->user()
            ->wishlists()
            ->pluck('product_slug')
            ->all()
        : [];
@endphp

@section('content')

<section
    class="tob-product-page"
    id="tobProductPage"
    data-product-slug="{{ $product['slug'] }}"
    data-product-name="{{ $product['name'] }}"
    data-product-price="{{ $product['price'] }}"
    data-product-image="{{ $productImage }}"
    data-product-stock="{{ $productStock }}"
    
    data-wholesale-enabled="{{ $wholesaleEnabled ? '1' : '0' }}"
    data-wholesale-moq="{{ $wholesaleMoq }}"
    data-product-min-quantity="{{ $minimumQuantity }}"
    data-product-max-quantity="{{ $maximumQuantity }}"

    data-checkout-url="{{ route('checkout') }}"
    data-stock-url="{{ route('cart.check-stock') }}"
>
    <div class="tob-product-container">

        {{-- BREADCRUMB --}}
        <nav
            class="tob-product-breadcrumb"
            aria-label="Breadcrumb"
        >
            <a href="{{ route('home') }}">Home</a>

            <span>›</span>

            <a href="{{ route('shop') }}">Cookies</a>

            <span>›</span>

            <a href="{{ route('shop', ['category' => $product['category']]) }}">
                {{ $categoryName }}
            </a>

            <span>›</span>

            <span>{{ $product['name'] }}</span>
        </nav>

        <div class="tob-product-main">

            {{-- PRODUCT GALLERY --}}
            <div class="tob-product-gallery">

                <div class="tob-product-thumbnails">
                    @foreach ($galleryImages as $index => $galleryImage)
                        <button
                            type="button"
                            class="tob-product-thumb {{ $index === 0 ? 'active' : '' }}"
                            data-product-thumbnail
                            data-image="{{ $galleryImage }}"
                            aria-label="View product image {{ $index + 1 }}"
                        >
                            <img
                                src="{{ $galleryImage }}"
                                alt="{{ $product['name'] }} view {{ $index + 1 }}"
                            >
                        </button>
                    @endforeach
                </div>

                <div class="tob-product-image-area">

                    <div class="tob-product-image-frame">

                        <span class="tob-product-image-badge">
                            PREMIUM
                        </span>

                        <img
                            id="tob-main-product-image"
                            class="tob-main-product-image"
                            data-product-main-image
                            src="{{ $galleryImages[0] }}"
                            alt="{{ $product['name'] }}"
                        >

                        <button
                            type="button"
                            class="tob-product-zoom"
                            aria-label="Zoom product image"
                        >
                            <svg
                                width="21"
                                height="21"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <circle cx="11" cy="11" r="7"></circle>
                                <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                                <line x1="11" y1="8" x2="11" y2="14"></line>
                                <line x1="8" y1="11" x2="14" y2="11"></line>
                            </svg>
                        </button>

                        <div class="tob-product-hand-note">
                            Made with<br>
                            love & goodness

                            <svg viewBox="0 0 150 25" aria-hidden="true">
                                <path
                                    d="M5 13 C35 24, 92 2, 145 10"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </div>
                    </div>

                    @if ($product['slug'] === 'gem-badam-pista')
                        <div class="tob-product-lifestyle">
                            <img
                                src="{{ asset('images/product-detail/jem-badam-pista-banner.jpg') }}"
                                alt="{{ $product['name'] }} cookies"
                            >
                        </div>
                    @endif

                </div>
            </div>

            {{-- PRODUCT DETAILS --}}
            <div class="tob-product-details">

                <div class="tob-product-topline">

                    <span class="tob-product-sku">
                        SKU: TOB-{{ strtoupper($product['slug']) }}
                    </span>

                    <button
                        type="button"
                        class="tob-product-wishlist {{ $productInWishlist ? 'active' : '' }}"
                        aria-label="{{ $productInWishlist
                            ? 'Remove ' . $product['name'] . ' from wishlist'
                            : 'Add ' . $product['name'] . ' to wishlist' }}"
                        aria-pressed="{{ $productInWishlist ? 'true' : 'false' }}"
                        data-wishlist-toggle
                        data-wishlist-slug="{{ $product['slug'] }}"
                        data-product-name="{{ $product['name'] }}"
                        data-wishlist-active="{{ $productInWishlist ? '1' : '0' }}"
                        data-login-url="{{ route('login') }}"
                        @auth
                            data-toggle-url="{{ route('wishlist.toggle', $product['slug']) }}"
                        @endauth
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8z"
                            />
                        </svg>
                    </button>

                </div>

                <h1 class="tob-product-title">
                    {{ strtoupper($product['name']) }}
                </h1>

                <p class="tob-product-subtitle">
                    {{ $categoryName }}
                </p>

                {{-- REAL REVIEW RATING --}}
                <div class="tob-product-rating">

                    <div
                        class="tob-rating-stars"
                        aria-label="{{ $averageRating }} out of 5 stars"
                    >
                        @for ($star = 1; $star <= 5; $star++)
                            {{ $star <= round($averageRating) ? '★' : '☆' }}
                        @endfor
                    </div>

                    <a href="#reviews">
                        @if ($reviewCount > 0)
                            {{ number_format($averageRating, 1) }}
                            ({{ $reviewCount }}
                            {{ \Illuminate\Support\Str::plural('Review', $reviewCount) }})
                        @else
                            No Reviews Yet
                        @endif
                    </a>

                    <span class="tob-rating-divider"></span>
                </div>

                
{{-- RETAIL PRICE --}}
<div class="tob-product-price-row">
    <span class="tob-current-price">
        ₹{{ number_format((float) $product['price'], 2) }}
    </span>
</div>

<p class="tob-tax-text">
    Inclusive of all taxes
</p>

{{-- WHOLESALE INFORMATION: APPROVED RETAILERS ONLY --}}
@if($wholesaleEnabled)
    <div
        class="tob-product-wholesale-info"
        style="
            margin: 18px 0;
            padding: 18px 20px;
            border: 1px solid #ead8c1;
            border-radius: 12px;
            background: #fff8ef;
        "
    >
        <div style="
            color: #76513a;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
        ">
            EXCLUSIVE WHOLESALE RATE
        </div>

        <div style="
            color: #237747;
            font-size: 27px;
            font-weight: 700;
            line-height: 1.3;
        ">
            ₹{{ number_format((float) $product['wholesale_price'], 2) }}
            <span style="font-size: 13px; font-weight: 400;">
                / unit
            </span>
        </div>

        <p style="
            margin: 10px 0 0;
            font-size: 14px;
            color: #76513a;
        ">
            Minimum wholesale order:
            <strong>
                {{ $product['wholesale_moq'] }}
                {{ (int) $product['wholesale_moq'] === 1 ? 'unit' : 'units' }}
            </strong>
        </p>

        <p style="
            margin: 10px 0 0;
            font-size: 12px;
            line-height: 1.6;
            color: #8a6a52;
        ">
            Wholesale pricing applies at checkout when
            the minimum order quantity is met.
        </p>
    </div>
@endif


                <div class="tob-product-description">
                    <p>
                        {{ $product['description']
                            ?: 'Freshly baked cookies from The Only Baker.' }}
                    </p>
                </div>

                <div class="tob-product-divider"></div>

                {{-- QUANTITY --}}
                <div class="tob-product-purchase">

                    <div class="tob-quantity-heading">
                        Quantity
                    </div>

                    <div class="tob-quantity-row">

                        <div class="tob-quantity-selector">

                            <button
                                type="button"
                                class="tob-qty-btn"
                                id="tobProductMinus"
                                aria-label="Decrease quantity"
                                @disabled(!$productInStock)
                            >
                                −
                            </button>

                            <input
                                type="number"
                                id="tobProductQuantity"
                                value="1"
                                min="1"
                                max="{{ $maximumQuantity }}"
                                readonly
                                @disabled(!$productInStock)
                            >

                            <button
                                type="button"
                                class="tob-qty-btn"
                                id="tobProductPlus"
                                aria-label="Increase quantity"
                                @disabled(!$productInStock)
                            >
                                +
                            </button>

                        </div>

                        @if ($productInStock)
                            <span class="tob-stock-status">
                                <span class="tob-stock-dot"></span>

                                @if ($productStock <= 5)
                                    Only {{ $productStock }} Left
                                @else
                                    In Stock
                                @endif
                            </span>
                        @else
                            <span class="tob-stock-status tob-out-of-stock">
                                <span class="tob-stock-dot"></span>
                                Out of Stock
                            </span>
                        @endif

                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="tob-product-actions">

                    <button
                        type="button"
                        class="tob-add-cart-btn"
                        id="tobProductAddCart"
                        @disabled(!$productInStock)
                    >
                        <svg
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="9" cy="20" r="1"></circle>
                            <circle cx="19" cy="20" r="1"></circle>
                            <path
                                d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"
                            ></path>
                        </svg>

                        <span>
                            {{ $productInStock
                                ? 'ADD TO CART'
                                : 'OUT OF STOCK' }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="tob-buy-now-btn"
                        id="tobProductBuyNow"
                        @disabled(!$productInStock)
                    >
                        {{ $productInStock
                            ? 'BUY NOW'
                            : 'OUT OF STOCK' }}
                    </button>

                </div>

                {{-- BENEFITS --}}
                <div class="tob-product-benefits">

                    <div class="tob-product-benefit">
                        <div class="tob-benefit-icon">♡</div>
                        <div>
                            <strong>Freshly Baked</strong>
                            <span>Made with care</span>
                        </div>
                    </div>

                    <div class="tob-product-benefit">
                        <div class="tob-benefit-icon">✦</div>
                        <div>
                            <strong>Premium Quality</strong>
                            <span>Carefully selected ingredients</span>
                        </div>
                    </div>

                    <div class="tob-product-benefit">
                        <div class="tob-benefit-icon">♧</div>
                        <div>
                            <strong>Quality Packaging</strong>
                            <span>Freshness protected</span>
                        </div>
                    </div>

                    <div class="tob-product-benefit">
                        <div class="tob-benefit-icon">♢</div>
                        <div>
                            <strong>Made with Love</strong>
                            <span>The Only Baker</span>
                        </div>
                    </div>

                </div>

                {{-- ACCORDIONS --}}
                <div class="tob-product-accordions">

                    <div class="tob-product-accordion active">

                        <button
                            type="button"
                            class="tob-accordion-trigger"
                            aria-expanded="true"
                        >
                            <span>Ingredients</span>
                            <span class="tob-accordion-icon">−</span>
                        </button>

                        <div class="tob-accordion-content">
                            <p>
                                Please refer to the product packaging
                                for the complete ingredients and
                                allergen information.
                            </p>
                        </div>

                    </div>

                    <div class="tob-product-accordion">

                        <button
                            type="button"
                            class="tob-accordion-trigger"
                            aria-expanded="false"
                        >
                            <span>Nutritional Information</span>
                            <span class="tob-accordion-icon">+</span>
                        </button>

                        <div class="tob-accordion-content">
                            <p>
                                Nutritional information may vary
                                depending on the product and batch.
                                Please check the product packaging.
                            </p>
                        </div>

                    </div>

                    <div class="tob-product-accordion">

                        <button
                            type="button"
                            class="tob-accordion-trigger"
                            aria-expanded="false"
                        >
                            <span>Storage Instructions</span>
                            <span class="tob-accordion-icon">+</span>
                        </button>

                        <div class="tob-accordion-content">
                            <p>
                                Store in a cool and dry place.
                                Keep the pack tightly sealed
                                after opening.
                            </p>
                        </div>

                    </div>

                    <div class="tob-product-accordion">

                        <button
                            type="button"
                            class="tob-accordion-trigger"
                            aria-expanded="false"
                        >
                            <span>Shipping & Delivery</span>
                            <span class="tob-accordion-icon">+</span>
                        </button>

                        <div class="tob-accordion-content">
                            <p>
                                Carefully packed to help maintain
                                freshness during delivery.
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</section>

{{-- CUSTOMER REVIEWS --}}
<section class="tob-reviews-section" id="reviews">

    <div class="tob-product-container">

        <div class="tob-reviews-heading">
            <span class="tob-reviews-eyebrow">
                CUSTOMER REVIEWS
            </span>

            <h2>What Our Customers Say</h2>
        </div>

        @if (session('review_success'))
            <div class="tob-review-success">
                {{ session('review_success') }}
            </div>
        @endif

        @if ($errors->has('rating') || $errors->has('review'))
            <div class="tob-review-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="tob-reviews-layout">

            {{-- RATING SUMMARY --}}
            <div class="tob-rating-summary">

                <div class="tob-rating-big">
                    {{ $reviewCount > 0
                        ? number_format($averageRating, 1)
                        : '0.0' }}
                </div>

                <div
                    class="tob-summary-stars"
                    aria-label="{{ $averageRating }} out of 5 stars"
                >
                    @for ($star = 1; $star <= 5; $star++)
                        <span class="{{ $star <= round($averageRating) ? 'filled' : '' }}">
                            ★
                        </span>
                    @endfor
                </div>

                <p>
                    Based on {{ $reviewCount }}
                    {{ \Illuminate\Support\Str::plural('review', $reviewCount) }}
                </p>

                <div class="tob-rating-bars">
                    @for ($rating = 5; $rating >= 1; $rating--)
                        @php
                            $ratingTotal = $ratingBreakdown[$rating] ?? 0;

                            $ratingPercent = $reviewCount > 0
                                ? ($ratingTotal / $reviewCount) * 100
                                : 0;
                        @endphp

                        <div class="tob-rating-bar-row">

                            <span>{{ $rating }} ★</span>

                            <div class="tob-rating-bar">
                                <span style="width: {{ $ratingPercent }}%"></span>
                            </div>

                            <strong>{{ $ratingTotal }}</strong>

                        </div>
                    @endfor
                </div>

            </div>

            {{-- REVIEW FORM --}}
            <div class="tob-review-form-card">

                @auth

                    <span class="tob-review-form-eyebrow">
                        {{ $userReview
                            ? 'YOUR REVIEW'
                            : 'SHARE YOUR EXPERIENCE' }}
                    </span>

                    <h3>
                        {{ $userReview
                            ? 'Update Your Review'
                            : 'Write a Review' }}
                    </h3>

                    <form
                        action="{{ route('product.review.store', $product['slug']) }}"
                        method="POST"
                        class="tob-review-form"
                        id="tobReviewForm"
                    >
                        @csrf

                        <div class="tob-review-rating-field">

                            <label>Your Rating</label>

                            <div
                                class="tob-review-star-input"
                                id="tobReviewStars"
                            >
                                @for ($rating = 1; $rating <= 5; $rating++)
                                    <button
                                        type="button"
                                        class="tob-review-star"
                                        data-rating="{{ $rating }}"
                                        aria-label="{{ $rating }} star rating"
                                    >
                                        ★
                                    </button>
                                @endfor
                            </div>

                            <input
                                type="hidden"
                                name="rating"
                                id="tobReviewRating"
                                value="{{ old('rating', $userReview?->rating ?? '') }}"
                            >

                            <span
                                class="tob-selected-rating"
                                id="tobSelectedRating"
                            ></span>

                        </div>

                        <div class="tob-review-text-field">

                            <label for="tobReviewText">
                                Your Review
                            </label>

                            <textarea
                                name="review"
                                id="tobReviewText"
                                maxlength="1500"
                                rows="6"
                                placeholder="Tell us what you loved about this cookie..."
                            >{{ old('review', $userReview?->review ?? '') }}</textarea>

                            <div class="tob-review-character-count">
                                <span id="tobReviewCharacterCount">0</span>
                                / 1500
                            </div>

                        </div>

                        <button
                            type="submit"
                            class="tob-submit-review"
                        >
                            {{ $userReview
                                ? 'UPDATE REVIEW'
                                : 'SUBMIT REVIEW' }}
                        </button>

                    </form>

                    @if ($userReview)
                        <form
                            action="{{ route('product.review.destroy', $product['slug']) }}"
                            method="POST"
                            class="tob-delete-review-form"
                            onsubmit="return confirm('Remove your review?');"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit">
                                Delete My Review
                            </button>
                        </form>
                    @endif

                @else

                    <span class="tob-review-form-eyebrow">
                        SHARE YOUR EXPERIENCE
                    </span>

                    <h3>Write a Review</h3>

                    <p class="tob-review-login-text">
                        Sign in to share your experience
                        with this product.
                    </p>

                    <a
                        href="{{ route('login') }}"
                        class="tob-review-login-button"
                    >
                        SIGN IN TO REVIEW
                    </a>

                @endauth

            </div>

        </div>

        {{-- REVIEWS LIST --}}
        <div class="tob-customer-reviews">

            <div class="tob-customer-reviews-head">
                <h3>Customer Reviews</h3>

                <span>
                    {{ $reviewCount }}
                    {{ \Illuminate\Support\Str::plural('Review', $reviewCount) }}
                </span>
            </div>

            @forelse ($reviews as $review)

                <article class="tob-review-item">

                    <div class="tob-review-avatar">
                        {{ strtoupper(
                            mb_substr(
                                $review->user->name ?? 'C',
                                0,
                                1
                            )
                        ) }}
                    </div>

                    <div class="tob-review-content">

                        <div class="tob-review-meta">

                            <div>
                                <strong>
                                    {{ $review->user->name ?? 'Customer' }}
                                </strong>

                                @if ($review->isVerifiedPurchase())
                                    <span
                                        class="tob-verified-customer"
                                        title="This customer purchased this product in a delivered order."
                                    >
                                        ✓ Verified Purchase
                                    </span>
                                @endif
                            </div>

                            <time datetime="{{ $review->created_at->toDateString() }}">
                                {{ $review->created_at->format('d M Y') }}
                            </time>

                        </div>

                        <div
                            class="tob-review-item-stars"
                            aria-label="{{ $review->rating }} out of 5 stars"
                        >
                            @for ($star = 1; $star <= 5; $star++)
                                <span class="{{ $star <= $review->rating ? 'filled' : '' }}">
                                    ★
                                </span>
                            @endfor
                        </div>

                        @if ($review->review)
                            <p>{{ $review->review }}</p>
                        @endif

                    </div>

                </article>

            @empty

                <div class="tob-no-reviews">

                    <div class="tob-no-review-stars">
                        ☆ ☆ ☆ ☆ ☆
                    </div>

                    <h3>Be the first to review</h3>

                    <p>
                        No customer reviews yet for
                        {{ $product['name'] }}.
                    </p>

                </div>

            @endforelse

        </div>

    </div>
</section>

{{-- RELATED PRODUCTS --}}
@if (count($relatedProducts) > 0)

<section class="tob-related-products">

    <div class="tob-product-container">

        <div class="tob-related-header">

            <div>
                <span class="tob-related-eyebrow">
                    YOU MAY ALSO LIKE
                </span>

                <h2>More Cookies to Love</h2>
            </div>

            <div class="tob-related-controls">

                <a href="{{ route('shop') }}">
                    VIEW ALL
                </a>

                <button
                    type="button"
                    class="tob-related-arrow"
                    data-direction="left"
                    aria-label="Previous products"
                >
                    ←
                </button>

                <button
                    type="button"
                    class="tob-related-arrow"
                    data-direction="right"
                    aria-label="Next products"
                >
                    →
                </button>

            </div>

        </div>

        <div
            class="tob-related-track"
            id="tob-related-track"
        >
            @foreach ($relatedProducts as $related)

                @php
                    $relatedInWishlist = in_array(
                        $related['slug'],
                        $relatedWishlistSlugs,
                        true
                    );

                    $relatedImage = !empty($related['image'])
                        ? asset($related['image'])
                        : asset('images/products/cherry-fruit.jpg');
                @endphp

                <article class="tob-related-card">

                    <div class="tob-related-image-wrap">

                        <a
                            href="{{ route('product.show', $related['slug']) }}"
                            class="tob-related-image"
                        >
                            <img
                                src="{{ $relatedImage }}"
                                alt="{{ $related['name'] }}"
                            >
                        </a>

                        <button
                            type="button"
                            class="tob-related-wishlist {{ $relatedInWishlist ? 'active' : '' }}"
                            aria-label="{{ $relatedInWishlist
                                ? 'Remove ' . $related['name'] . ' from wishlist'
                                : 'Add ' . $related['name'] . ' to wishlist' }}"
                            aria-pressed="{{ $relatedInWishlist ? 'true' : 'false' }}"
                            data-wishlist-toggle
                            data-wishlist-slug="{{ $related['slug'] }}"
                            data-product-name="{{ $related['name'] }}"
                            data-wishlist-active="{{ $relatedInWishlist ? '1' : '0' }}"
                            data-login-url="{{ route('login') }}"
                            @auth
                                data-toggle-url="{{ route('wishlist.toggle', $related['slug']) }}"
                            @endauth
                        >
                            ♡
                        </button>

                    </div>

                    <div class="tob-related-info">

                        <h3>
                            <a href="{{ route('product.show', $related['slug']) }}">
                                {{ $related['name'] }}
                            </a>
                        </h3>

                        <span>
                            ₹{{ number_format((float) $related['price'], 2) }}
                        </span>

                    </div>

                </article>

            @endforeach
        </div>

    </div>
</section>

@endif

@endsection

@push('scripts')
    <script src="{{ asset('js/product.js') }}"></script>
    <script src="{{ asset('js/reviews.js') }}"></script>
@endpush
