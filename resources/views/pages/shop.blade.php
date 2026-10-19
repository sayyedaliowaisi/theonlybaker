@extends('layouts.app')

@section('title', 'Cookies | The Only Baker')

@push('styles')

    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">

@endpush

@php

    /*

    |--------------------------------------------------------------------------

    | WISHLIST

    |--------------------------------------------------------------------------

    */

    $categoryCounts = [];

    foreach ($products as $item) {

        $category = $item['category'] ?? '';

        $categoryCounts[$category] = ($categoryCounts[$category] ?? 0) + 1;

    }

    $totalProducts = count($products);

    $wishlistSlugs = auth()->check()

        ? auth()->user()

            ->wishlists()

            ->pluck('product_slug')

            ->all()

        : [];

@endphp

@section('content')

<div class="tob-shop-page">

    {{-- =========================================================

         HERO

    ========================================================== --}}

    <section class="tob-cookie-hero">

        <div class="tob-cookie-hero-bg">

            <img

                src="{{ asset('images/shop/shop-hero.jpg') }}"

                alt="The Only Baker Cookie Collection"

            >

        </div>

        <div class="tob-hero-leaf" aria-hidden="true">

            <svg viewBox="0 0 180 330">

                <path

                    class="tob-leaf-stem"

                    d="M18 330C30 275 47 228 72 185C96 143 119 102 145 40"

                />

                <path

                    class="tob-leaf"

                    d="M45 255C8 248 1 219 7 194C37 198 55 220 45 255Z"

                />

                <path

                    class="tob-leaf"

                    d="M68 207C32 191 35 160 49 139C78 151 88 178 68 207Z"

                />

                <path

                    class="tob-leaf"

                    d="M92 162C62 141 70 111 88 94C113 111 116 139 92 162Z"

                />

                <path

                    class="tob-leaf"

                    d="M111 119C91 93 104 69 125 58C144 81 137 105 111 119Z"

                />

            </svg>

        </div>

        <div class="tob-cookie-hero-content">

            <span class="tob-cookie-hero-eyebrow">

                OUR COOKIE COLLECTION

            </span>

            <h1>

                Small Bites.<br>

                Big Happiness.

            </h1>

            <p class="tob-cookie-hero-description">

                Discover our handcrafted cookies, made with<br class="tob-desktop-break">

                premium ingredients and timeless recipes.

            </p>

            <div class="tob-cookie-hero-features">

                <div class="tob-cookie-hero-feature">

                    <div class="tob-cookie-feature-icon">

                        <svg viewBox="0 0 40 40" aria-hidden="true">

                            <path

                                d="M31 7C18 8 9 16 9 28c0 3 1 5 2 7M30 8C27 19 20 27 11 34"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1.6"

                            />

                        </svg>

                    </div>

                    <span>

                        Premium<br>

                        Ingredients

                    </span>

                </div>

                <div class="tob-cookie-feature-divider"></div>

                <div class="tob-cookie-hero-feature">

                    <div class="tob-cookie-feature-icon">

                        <svg viewBox="0 0 40 40" aria-hidden="true">

                            <path

                                d="M20 34S6 26 6 15c0-6 7-9 11-4l3 4 3-4c4-5 11-2 11 4 0 11-14 19-14 19Z"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1.6"

                            />

                        </svg>

                    </div>

                    <span>

                        Baked<br>

                        with Care

                    </span>

                </div>

                <div class="tob-cookie-feature-divider"></div>

                <div class="tob-cookie-hero-feature">

                    <div class="tob-cookie-feature-icon">

                        <svg viewBox="0 0 40 40" aria-hidden="true">

                            <circle

                                cx="20"

                                cy="20"

                                r="15"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1.5"

                            />

                            <circle

                                cx="15"

                                cy="16"

                                r="1.3"

                                fill="currentColor"

                            />

                            <circle

                                cx="25"

                                cy="16"

                                r="1.3"

                                fill="currentColor"

                            />

                            <path

                                d="M13 23c2 5 12 5 14 0"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1.5"

                                stroke-linecap="round"

                            />

                        </svg>

                    </div>

                    <span>

                        Happiness<br>

                        in Every Bite

                    </span>

                </div>

            </div>

        </div>

        <div class="tob-cookie-hero-note" aria-hidden="true">

            <span>Cookies make</span>

            <strong>everything</strong>

            <span>better</span>

            <svg viewBox="0 0 130 45">

                <path d="M4 30C30 7 68 8 99 20C113 25 120 25 126 15"/>

            </svg>

        </div>

    </section>

    {{-- =========================================================

         BREADCRUMB

    ========================================================== --}}

    <div class="tob-shop-breadcrumb-wrap">

        <div class="tob-shop-main-container">

            <nav

                class="tob-shop-breadcrumb"

                aria-label="Breadcrumb"

            >

                <a href="{{ route('home') }}">

                    Home

                </a>

                <span>›</span>

                <span>Cookies</span>

            </nav>

        </div>

    </div>

    {{-- =========================================================

         MAIN SHOP AREA

    ========================================================== --}}

    <section class="tob-shop-content">

        <div class="tob-shop-main-container">

            <div class="tob-shop-layout">

                {{-- =================================================

                     SIDEBAR

                ================================================== --}}

                <aside

                    class="tob-shop-sidebar"

                    id="tobShopSidebar"

                >

                    {{-- CATEGORIES --}}

                    <div class="tob-shop-sidebar-box">

                        <button

                            type="button"

                            class="tob-sidebar-title"

                            data-sidebar-toggle

                        >

                            <span class="tob-sidebar-title-left">

                                <svg viewBox="0 0 32 32" aria-hidden="true">

                                    <circle

                                        cx="16"

                                        cy="16"

                                        r="11"

                                        fill="none"

                                        stroke="currentColor"

                                        stroke-width="1.4"

                                    />

                                    <circle

                                        cx="12"

                                        cy="12"

                                        r="1.3"

                                        fill="currentColor"

                                    />

                                    <circle

                                        cx="19"

                                        cy="10"

                                        r="1.3"

                                        fill="currentColor"

                                    />

                                    <circle

                                        cx="21"

                                        cy="18"

                                        r="1.3"

                                        fill="currentColor"

                                    />

                                    <circle

                                        cx="13"

                                        cy="21"

                                        r="1.3"

                                        fill="currentColor"

                                    />

                                </svg>

                                <span>Categories</span>

                            </span>

                            <svg

                                class="tob-sidebar-chevron"

                                viewBox="0 0 12 8"

                                aria-hidden="true"

                            >

                                <path d="M1 6.5 6 1.5l5 5"/>

                            </svg>

                        </button>

                        <div class="tob-sidebar-body">

                            <button

                                type="button"

                                class="tob-category-filter active"

                                data-category="all"

                            >

                                <span class="tob-category-filter-icon">◉</span>

                                <span>All Cookies ({{ $totalProducts }})</span>

                            </button>

                            <button

                                type="button"

                                class="tob-category-filter"

                                data-category="dry-fruit"

                            >

                                <span class="tob-category-filter-icon">◌</span>

                                <span>Dry Fruit Cookies ({{ $categoryCounts['dry-fruit'] ?? 0 }})</span>

                            </button>

                            <button

                                type="button"

                                class="tob-category-filter"

                                data-category="chocolate"

                            >

                                <span class="tob-category-filter-icon">◌</span>

                                <span>Chocolate Cookies ({{ $categoryCounts['chocolate'] ?? 0 }})</span>

                            </button>

                            <button

                                type="button"

                                class="tob-category-filter"

                                data-category="classic"

                            >

                                <span class="tob-category-filter-icon">◌</span>

                                <span>Classic Cookies ({{ $categoryCounts['classic'] ?? 0 }})</span>

                            </button>

                        </div>

                    </div>

                    {{-- FILTERS --}}

                    <div class="tob-shop-sidebar-box tob-filter-box">

                        <button

                            type="button"

                            class="tob-sidebar-title"

                            data-sidebar-toggle

                        >

                            <span class="tob-sidebar-title-left">

                                <svg viewBox="0 0 32 32" aria-hidden="true">

                                    <path

                                        d="M6 7h20L19 16v8l-6 3V16L6 7Z"

                                        fill="none"

                                        stroke="currentColor"

                                        stroke-width="1.5"

                                        stroke-linejoin="round"

                                    />

                                </svg>

                                <span>Filters</span>

                            </span>

                            <svg

                                class="tob-sidebar-chevron"

                                viewBox="0 0 12 8"

                                aria-hidden="true"

                            >

                                <path d="M1 6.5 6 1.5l5 5"/>

                            </svg>

                        </button>

                        <div class="tob-sidebar-body">

                            <div class="tob-filter-group">

                                <h3>Price Range</h3>

                                <label class="tob-filter-checkbox">

                                    <input

                                        type="checkbox"

                                        value="under-200"

                                        data-price-filter

                                    >

                                    <span class="tob-custom-checkbox"></span>

                                    <span>Under ₹200</span>

                                </label>

                                <label class="tob-filter-checkbox">

                                    <input

                                        type="checkbox"

                                        value="200-300"

                                        data-price-filter

                                    >

                                    <span class="tob-custom-checkbox"></span>

                                    <span>₹200 – ₹300</span>

                                </label>

                                <label class="tob-filter-checkbox">

                                    <input

                                        type="checkbox"

                                        value="300-400"

                                        data-price-filter

                                    >

                                    <span class="tob-custom-checkbox"></span>

                                    <span>₹300 – ₹400</span>

                                </label>

                                <label class="tob-filter-checkbox">

                                    <input

                                        type="checkbox"

                                        value="above-400"

                                        data-price-filter

                                    >

                                    <span class="tob-custom-checkbox"></span>

                                    <span>Above ₹400</span>

                                </label>

                            </div>

                            <div class="tob-filter-group tob-dietary-group">

                                <h3>Dietary</h3>

                                <label class="tob-filter-checkbox">

                                    <input

                                        type="checkbox"

                                        value="eggless"

                                        data-dietary-filter

                                    >

                                    <span class="tob-custom-checkbox"></span>

                                    <span>Eggless</span>

                                </label>

                                <label class="tob-filter-checkbox">

                                    <input

                                        type="checkbox"

                                        value="no-preservatives"

                                        data-dietary-filter

                                    >

                                    <span class="tob-custom-checkbox"></span>

                                    <span>No Preservatives</span>

                                </label>

                            </div>

                        </div>

                    </div>

                    {{-- GIFTING --}}

                    <div

                        class="tob-gifting-card"

                        id="gifting"

                    >

                        <img

                            src="{{ asset('images/shop/gifting-cookie-box.jpg') }}"

                            alt="Cookie Gifting"

                        >

                        <div class="tob-gifting-card-content">

                            <p>

                                Happiness<br>

                                Looks Better<br>

                                in a Cookie Box

                            </p>

                            <a href="#gifting">

                                <span>EXPLORE GIFTING</span>

                                <span>→</span>

                            </a>

                        </div>

                    </div>

                </aside>

                {{-- =================================================

                     PRODUCTS

                ================================================== --}}

                <div class="tob-products-area">

                    <div class="tob-products-header">

                        <div>

                            <h2>Our Cookies</h2>

                            <p id="tobProductCount">

                                {{ $totalProducts }} Delicious Varieties

                            </p>

                        </div>

                        <div class="tob-sort-wrap">

                            <label for="tobProductSort">

                                Sort by:

                            </label>

                            <select id="tobProductSort">

                                <option value="featured">

                                    Featured

                                </option>

                                <option value="price-low">

                                    Price: Low to High

                                </option>

                                <option value="price-high">

                                    Price: High to Low

                                </option>

                                <option value="name">

                                    Name

                                </option>

                            </select>

                        </div>

                    </div>

                    {{-- PRODUCT GRID --}}

                    <div

                        class="tob-products-grid"

                        id="tobProductsGrid"

                    >

                        @foreach($products as $product)

                            @php

                                /*

                                |--------------------------------------------------------------------------

                                | PRODUCT STOCK

                                |--------------------------------------------------------------------------

                                */

                                $productStock = max(

                                    0,

                                    (int) ($product['stock'] ?? 0)

                                );

                                $productInStock =

                                    $productStock > 0;

                                $productImage = !empty($product['image'])

                                    ? asset($product['image'])

                                    : asset('images/products/cherry-fruit.jpg');

                            @endphp

                            <article

                                class="tob-product-card {{ !$productInStock ? 'tob-product-out-of-stock' : '' }}"

                                data-product-card

                                data-category="{{ $product['category'] }}"

                                data-price="{{ $product['price'] }}"

                                data-name="{{ strtolower($product['name']) }}"

                                data-product-name="{{ $product['name'] }}"

                                data-slug="{{ $product['slug'] }}"

                                data-image="{{ $productImage }}"

                                data-number="{{ $product['number'] ?? $loop->iteration }}"

                                data-stock="{{ $productStock }}"

                                data-in-stock="{{ $productInStock ? '1' : '0' }}"

                            >

                                {{-- PRODUCT IMAGE --}}

                                <div class="tob-product-image">

                                    <a

                                        href="{{ route('product.show', $product['slug']) }}"

                                        class="tob-product-image-link"

                                        aria-label="View {{ $product['name'] }}"

                                    >

                                        <img

                                            src="{{ $productImage }}"

                                            alt="{{ $product['name'] }}"

                                            loading="lazy"

                                        >

                                    </a>

                                    {{-- STOCK BADGE --}}

                                    @if (!$productInStock)

                                        <span class="tob-shop-stock-badge">

                                            OUT OF STOCK

                                        </span>

                                    @elseif ($productStock <= 5)

                                        <span class="tob-shop-stock-badge tob-low-stock-badge">

                                            ONLY {{ $productStock }} LEFT

                                        </span>

                                    @endif

                                    {{-- WISHLIST --}}

                                    <button

                                        type="button"

                                        class="tob-product-wishlist {{ in_array($product['slug'], $wishlistSlugs, true) ? 'active' : '' }}"

                                        aria-label="{{ in_array($product['slug'], $wishlistSlugs, true) ? 'Remove ' . $product['name'] . ' from wishlist' : 'Add ' . $product['name'] . ' to wishlist' }}"

                                        aria-pressed="{{ in_array($product['slug'], $wishlistSlugs, true) ? 'true' : 'false' }}"

                                        data-wishlist-toggle

                                        data-wishlist-slug="{{ $product['slug'] }}"

                                        data-product-name="{{ $product['name'] }}"

                                        data-wishlist-active="{{ in_array($product['slug'], $wishlistSlugs, true) ? '1' : '0' }}"

                                        data-login-url="{{ route('login') }}"

                                        @auth

                                            data-toggle-url="{{ route('wishlist.toggle', $product['slug']) }}"

                                        @endauth

                                    >

                                        <svg

                                            viewBox="0 0 32 32"

                                            aria-hidden="true"

                                        >

                                            <path

                                                d="M16 27S5 21 5 12.5C5 8 10 5.5 13.5 9L16 12l2.5-3C22 5.5 27 8 27 12.5 27 21 16 27 16 27Z"

                                                fill="none"

                                                stroke="currentColor"

                                                stroke-width="1.5"

                                            />

                                        </svg>

                                    </button>

                                </div>

                                {{-- PRODUCT INFO --}}

                                <div class="tob-product-info">

                                    <h3 class="tob-product-name">

                                        <a href="{{ route('product.show', $product['slug']) }}">

                                            {{ $product['number'] ?? $loop->iteration }}.

                                            {{ $product['name'] }}

                                        </a>

                                    </h3>

{{-- PRODUCT PRICE --}}

<div class="tob-product-price">

    ₹{{ number_format((float) $product['price'], 2) }}

</div>

{{-- WHOLESALE INFORMATION: APPROVED RETAILERS ONLY --}}

@if(!empty($product['has_wholesale_pricing']))

    <div

        class="tob-product-wholesale-info"

        style="

            margin-top: 9px;

            padding: 10px 12px;

            border: 1px solid #ead8c1;

            border-radius: 9px;

            background: #fff8ef;

            font-size: 13px;

            line-height: 1.6;

        "

    >

        <div style="color: #76513a; font-weight: 600;">

            Approved Retailer Rate

        </div>

        <strong style="color: #237747; font-size: 17px;">

            ₹{{ number_format((float) $product['wholesale_price'], 2) }}

        </strong>

        <div style="color: #76513a;">

            Minimum order:

            {{ $product['wholesale_moq'] }}

            {{ (int) $product['wholesale_moq'] === 1 ? 'unit' : 'units' }}

        </div>
        <small style="display: block; margin-top: 5px; color: #237747; font-weight: 600;">
            Wholesale pricing applies at checkout when the minimum order quantity is met.
        </small>

    </div>

@endif

                                    {{-- STOCK STATUS --}}

                                    @if ($productInStock)

                                        <div class="tob-shop-stock-status">

                                            <span class="tob-shop-stock-dot"></span>

                                            @if ($productStock <= 5)

                                                Only {{ $productStock }} Left

                                            @else

                                                In Stock

                                            @endif

                                        </div>

                                    @else

                                        <div class="tob-shop-stock-status tob-shop-out-of-stock">

                                            <span class="tob-shop-stock-dot"></span>

                                            Out of Stock

                                        </div>

                                    @endif

                                    {{-- VIEW DETAILS — CART ACTION MOVED TO PRODUCT PAGE --}}
                                    <a
                                        href="{{ route('product.show', $product['slug']) }}"
                                        class="tob-add-cart tob-view-details"
                                        style="text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;"
                                        aria-label="View details of {{ $product['name'] }}"
                                    >
                                        <span>View Details</span>
                                        <span aria-hidden="true">→</span>
                                    </a>

                                </div>

                            </article>

                        @endforeach

                    </div>

                    {{-- NO RESULTS --}}

                    <div

                        class="tob-no-products"

                        id="tobNoProducts"

                        hidden

                    >

                        No cookies found.

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- =========================================================

         BENEFITS

    ========================================================== --}}

    <section class="tob-shop-benefits">

        <div class="tob-shop-main-container">

            <div class="tob-benefits-grid">

                <div class="tob-benefit-item">

                    <div class="tob-benefit-icon">

                        <svg viewBox="0 0 48 48" aria-hidden="true">

                            <circle

                                cx="24"

                                cy="24"

                                r="21"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1"

                            />

                            <path

                                d="M31 12C20 13 14 20 14 30c0 2 1 4 2 6M31 12c-2 9-8 17-16 24"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1.4"

                            />

                        </svg>

                    </div>

                    <div>

                        <h3>

                            Premium Ingredients

                        </h3>

                        <p>

                            Sourced with care

                        </p>

                    </div>

                </div>

                <div class="tob-benefit-item">

                    <div class="tob-benefit-icon">

                        <svg viewBox="0 0 48 48" aria-hidden="true">

                            <circle

                                cx="24"

                                cy="24"

                                r="21"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1"

                            />

                            <path

                                d="M17 30c-3-5 0-14 7-16 7 2 10 11 7 16-3 5-11 5-14 0ZM13 35 35 13"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1.3"

                            />

                        </svg>

                    </div>

                    <div>

                        <h3>

                            Eggless Goodness

                        </h3>

                        <p>

                            For everyone to enjoy

                        </p>

                    </div>

                </div>

                <div class="tob-benefit-item">

                    <div class="tob-benefit-icon">

                        <svg viewBox="0 0 48 48" aria-hidden="true">

                            <circle

                                cx="24"

                                cy="24"

                                r="21"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1"

                            />

                            <path

                                d="M24 35c-7-4-9-11-4-17 1 4 3 5 5 6-1-5 1-9 5-12 1 5 5 8 5 13 0 6-5 10-11 10Z"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1.3"

                            />

                        </svg>

                    </div>

                    <div>

                        <h3>

                            No Preservatives

                        </h3>

                        <p>

                            Just real ingredients

                        </p>

                    </div>

                </div>

                <div class="tob-benefit-item">

                    <div class="tob-benefit-icon">

                        <svg viewBox="0 0 48 48" aria-hidden="true">

                            <circle

                                cx="24"

                                cy="24"

                                r="21"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1"

                            />

                            <path

                                d="M15 20h18v16H15V20Zm-2-5h22v5H13v-5Zm11 5v16"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1.2"

                            />

                        </svg>

                    </div>

                    <div>

                        <h3>

                            Secure Packaging

                        </h3>

                        <p>

                            Freshness guaranteed

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection

@push('scripts')

    <script src="{{ asset('js/shop.js') }}"></script>

@endpush
