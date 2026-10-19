
@extends('layouts.app')

@section('title', 'Our Collections | The Only Baker')

@section(
    'meta_description',
    "Explore The Only Baker's premium cookie collections, including chocolate, dry fruit and classic cookies, gifting favourites and more."
)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/collections.css') }}">
@endpush

@section('content')

<main class="tob-collections-page">

    {{-- HERO --}}
    <section class="tob-collections-hero">
        <div class="tob-collections-container">
            <div class="tob-collections-hero-content">

                <span class="tob-collections-eyebrow">
                    THE ONLY BAKER
                </span>

                <h1>
                    A Collection for
                    <em>Every Craving.</em>
                </h1>

                <p>
                    From rich chocolate indulgences to timeless
                    classics, discover cookies crafted for
                    every mood and every moment.
                </p>

                <a href="#shop-by-category" class="tob-collections-btn">
                    Explore Collections
                    <span aria-hidden="true">↗</span>
                </a>

            </div>

            <div class="tob-collections-hero-image">
                <img
    src="{{ asset('images/collections/collections-hero.jpg') }}"
    alt="The Only Baker premium cookie collections"
    loading="eager"
    fetchpriority="high"
    decoding="async"
>
            </div>
        </div>
    </section>

    
{{-- COLLECTIONS QUICK NAVIGATION --}}
<nav class="tob-collections-quick-nav" aria-label="Collections navigation">
    <div class="tob-collections-container">

        <div class="tob-collections-nav-list">

            <a href="#shop-by-category" class="tob-collections-nav-link">
                Categories
            </a>

            <a href="#featured-cookies" class="tob-collections-nav-link">
                Featured Cookies
            </a>

            <a href="#gifting-collection" class="tob-collections-nav-link">
                Gifting
            </a>

            <a href="#why-our-cookies" class="tob-collections-nav-link">
                Why Choose Us
            </a>

            <a href="#curated-collections" class="tob-collections-nav-link">
                Curated Collections
            </a>

            <a href="#shop-by-mood" class="tob-collections-nav-link">
                Shop by Mood
            </a>

        </div>

    </div>
</nav>


    {{-- CATEGORY COLLECTIONS --}}
    <section
        class="tob-collections-section"
        id="shop-by-category"
    >
        <div class="tob-collections-container">

            <div class="tob-collections-section-heading">
                <span class="tob-collections-eyebrow">
                    FIND YOUR FAVOURITE
                </span>

                <h2>Shop by Category</h2>

                <p>
                    Three delicious ways to enjoy
                    The Only Baker.
                </p>
            </div>

            <div class="tob-collections-category-grid">

                <a
                    href="{{ route('shop', ['category' => 'dry-fruit']) }}"
                    class="tob-collection-category-card"
                >
                    <div class="tob-collection-card-image">
                        <img
                            src="{{ asset('images/collections/dry-fruit.jpg') }}"
                            alt="Dry Fruit Cookies"
                            loading="lazy"
                        >
                    </div>

                    <div class="tob-collection-card-content">
                        <span>01 / PREMIUM & NUTTY</span>
                        <h3>Dry Fruit Cookies</h3>
                        <p>
                            Rich, crunchy cookies made
                            with delightful dry fruits.
                        </p>
                        <strong>Explore Collection →</strong>
                    </div>
                </a>

                <a
                    href="{{ route('shop', ['category' => 'chocolate']) }}"
                    class="tob-collection-category-card"
                >
                    <div class="tob-collection-card-image">
                        <img
                            src="{{ asset('images/collections/chocolate.jpg') }}"
                            alt="Chocolate Cookies"
                            loading="lazy"
                        >
                    </div>

                    <div class="tob-collection-card-content">
                        <span>02 / RICH & INDULGENT</span>
                        <h3>Chocolate Cookies</h3>
                        <p>
                            Chocolate-rich goodness
                            in every delicious bite.
                        </p>
                        <strong>Explore Collection →</strong>
                    </div>
                </a>

                <a
                    href="{{ route('shop', ['category' => 'classic']) }}"
                    class="tob-collection-category-card"
                >
                    <div class="tob-collection-card-image">
                        <img
                            src="{{ asset('images/collections/classic.jpg') }}"
                            alt="Classic Cookies"
                            loading="lazy"
                        >
                    </div>

                    <div class="tob-collection-card-content">
                        <span>03 / TIMELESS FAVOURITES</span>
                        <h3>Classic Cookies</h3>
                        <p>
                            Familiar flavours,
                            baked with a little extra love.
                        </p>
                        <strong>Explore Collection →</strong>
                    </div>
                </a>

            </div>
        </div>
    </section>

    
{{-- FEATURED COOKIES --}}
<section
    class="tob-featured-cookies"
    id="featured-cookies"
>
    <div class="tob-collections-container">

        <div class="tob-collections-section-heading">
            <span class="tob-collections-eyebrow">
                HANDPICKED FOR YOU
            </span>

            <h2>Our Featured Cookies</h2>

            <p>
                Discover some of our favourite creations,
                baked with care and made to delight.
            </p>
        </div>

        @if(!empty($bestSellingCookies))

            <div class="tob-featured-grid">

                @foreach($bestSellingCookies as $product)

                    @php
                        $stock = (int) ($product['stock'] ?? 0);

                        $isWholesale = (bool) (
                            $product['has_wholesale_pricing'] ?? false
                        );

                        $displayPrice = $isWholesale
                            ? ($product['wholesale_price'] ?? $product['price'])
                            : $product['price'];

                        $image = $product['image'] ?? null;
                    @endphp

                    <article class="tob-featured-card">

                        <a
                            href="{{ route('product.show', $product['slug']) }}"
                            class="tob-featured-image"
                            aria-label="View {{ $product['name'] }}"
                        >
                            @if($image)
                                <img
                                    src="{{ asset($image) }}"
                                    alt="{{ $product['name'] }}"
                                    loading="lazy"
                                    decoding="async"
                                >
                            @else
                                <div class="tob-featured-no-image">
                                    Image Coming Soon
                                </div>
                            @endif

                            @if($stock > 0)
                                <span class="tob-featured-badge">
                                    Featured
                                </span>
                            @else
                                <span class="tob-featured-badge tob-featured-soldout">
                                    Sold Out
                                </span>
                            @endif
                        </a>

                        <div class="tob-featured-content">

                            <span class="tob-featured-category">
                                {{ $product['category'] ?? 'Cookies' }}
                            </span>

                            <h3>
                                <a href="{{ route('product.show', $product['slug']) }}">
                                    {{ $product['name'] }}
                                </a>
                            </h3>

                            <div class="tob-featured-price">
                                ₹{{ number_format((float) $displayPrice, 2) }}

                                @if($isWholesale)
                                    <span>Wholesale price</span>
                                @endif
                            </div>

                            @if($isWholesale && !empty($product['wholesale_moq']))
                                <p class="tob-featured-moq">
                                    Min. {{ $product['wholesale_moq'] }} units
                                </p>
                            @endif

                            <a
                                href="{{ route('product.show', $product['slug']) }}"
                                class="tob-featured-view"
                            >
                                View Product
                                <span aria-hidden="true">→</span>
                            </a>

                        </div>

                    </article>

                @endforeach

            </div>

            <div class="tob-featured-footer">
                <a href="{{ route('shop') }}" class="tob-collections-btn">
                    Explore All Cookies
                    <span aria-hidden="true">→</span>
                </a>
            </div>

        @else

            <div class="tob-featured-empty">
                <p>
                    Our featured selection is being prepared.
                    Explore all our cookies in the meantime.
                </p>

                <a href="{{ route('shop') }}">
                    Browse All Cookies →
                </a>
            </div>

        @endif

    </div>
</section>


{{-- PREMIUM GIFTING BANNER --}}
<section class="tob-gifting-banner" id="gifting-collection">

    <div class="tob-collections-container">

        <div class="tob-gifting-banner-inner">

            <div class="tob-gifting-banner-image">
                <img
                    src="{{ asset('images/collections/gifting.jpg') }}"
                    alt="Premium cookie gifting collection by The Only Baker"
                    loading="lazy"
                >
            </div>

            <div class="tob-gifting-banner-content">

                <span class="tob-collections-eyebrow">
                    THE ART OF GIVING
                </span>

                <h2>
                    Sweet Moments,
                    <em>Beautifully Gifted.</em>
                </h2>

                <p>
                    Celebrate the people who matter with
                    thoughtfully crafted cookies. Because
                    every beautiful moment deserves
                    something delicious.
                </p>

                <div class="tob-gifting-banner-actions">

                    <a
                        href="{{ route('shop') }}"
                        class="tob-collections-btn"
                    >
                        Explore Cookies
                        <span aria-hidden="true">→</span>
                    </a>

                    <a
                        href="#curated-collections"
                        class="tob-gifting-secondary-link"
                    >
                        Discover Collections
                        <span aria-hidden="true">↗</span>
                    </a>

                </div>

                <div class="tob-gifting-banner-note">
                    <span aria-hidden="true">✦</span>
                    Baked with love. Made for sharing.
                </div>

            </div>

        </div>

    </div>

</section>


{{-- WHY OUR COOKIES ARE SPECIAL --}}
<section class="tob-cookie-special" id="why-our-cookies">
    <div class="tob-collections-container">

        <div class="tob-collections-section-heading">
            <span class="tob-collections-eyebrow">
                THE ONLY BAKER PROMISE
            </span>

            <h2>What Makes Our Cookies Special?</h2>

            <p>
                Thoughtfully crafted, carefully baked,
                and made for moments worth sharing.
            </p>
        </div>

        <div class="tob-cookie-special-grid">

            {{-- PREMIUM INGREDIENTS --}}
            <article class="tob-cookie-special-card">
                <div class="tob-cookie-special-icon">
                    <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <path d="M24 41V18M24 26C13 26 10 19 11 11C20 11 24 17 24 26ZM24 21C24 12 30 7 38 7C39 16 34 21 24 21Z"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 41H32" stroke="currentColor"
                              stroke-width="1.7" stroke-linecap="round"/>
                    </svg>
                </div>

                <h3>Premium Ingredients</h3>
                <p>
                    Carefully selected ingredients
                    for delicious flavour in every bite.
                </p>
            </article>

            {{-- FRESHLY BAKED --}}
            <article class="tob-cookie-special-card">
                <div class="tob-cookie-special-icon">
                    <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <circle cx="24" cy="24" r="17"
                                stroke="currentColor" stroke-width="1.7"/>
                        <path d="M24 14V24L31 29"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M24 4V7M24 41V44M4 24H7M41 24H44"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linecap="round"/>
                    </svg>
                </div>

                <h3>Freshly Baked</h3>
                <p>
                    Prepared with attention and care
                    to bring you delightful freshness.
                </p>
            </article>

            {{-- NO PRESERVATIVES --}}
            <article class="tob-cookie-special-card">
                <div class="tob-cookie-special-icon">
                    <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <path d="M24 5L39 11V22C39 32 33 39 24 43C15 39 9 32 9 22V11L24 5Z"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linejoin="round"/>
                        <path d="M17 24L22 29L32 19"
                              stroke="currentColor" stroke-width="1.9"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <h3>No Preservatives</h3>
                <p>
                    Simple, thoughtful baking
                    without added preservatives.
                </p>
            </article>

            {{-- CRAFTED WITH CARE --}}
            <article class="tob-cookie-special-card">
                <div class="tob-cookie-special-icon">
                    <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <path d="M24 40L8 25C1 18 11 7 19 14L24 19L29 14C37 7 47 18 40 25L24 40Z"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <h3>Crafted With Care</h3>
                <p>
                    Every cookie is made with
                    dedication, passion and love.
                </p>
            </article>

        </div>

    </div>
</section>




    {{-- CURATED COLLECTIONS --}}
    <section class="tob-curated-section" id="curated-collections">
        <div class="tob-collections-container">

            <div class="tob-collections-section-heading">
                <span class="tob-collections-eyebrow">
                    HANDPICKED WITH LOVE
                </span>

                <h2>Curated Collections</h2>

                <p>
                    Beautifully inspired selections
                    for life's little celebrations.
                </p>
            </div>

            <div class="tob-curated-grid">

                <article class="tob-curated-card">
                    <img
                        src="{{ asset('images/collections/best-sellers.jpg') }}"
                        alt="Best Sellers"
                        loading="lazy"
                    >

                    <div class="tob-curated-card-content">
                        <span>THE CROWD FAVOURITES</span>
                        <h3>Best Sellers</h3>
                        <p>
                            Discover flavours worth
                            coming back for.
                        </p>
                    </div>
                </article>

                <article class="tob-curated-card">
                    <img
                        src="{{ asset('images/collections/signature.jpg') }}"
                        alt="Signature Selection"
                        loading="lazy"
                    >

                    <div class="tob-curated-card-content">
                        <span>CRAFTED WITH CARE</span>
                        <h3>Signature Selection</h3>
                        <p>
                            A celebration of our
                            baking craftsmanship.
                        </p>
                    </div>
                </article>

                <article class="tob-curated-card">
                    <img
                        src="{{ asset('images/collections/gifting.jpg') }}"
                        alt="Gifting Favourites"
                        loading="lazy"
                    >

                    <div class="tob-curated-card-content">
                        <span>SHARE THE HAPPINESS</span>
                        <h3>Gifting Favourites</h3>
                        <p>
                            Thoughtful inspiration
                            for sweeter surprises.
                        </p>
                    </div>
                </article>

                <article class="tob-curated-card">
                    <img
                        src="{{ asset('images/collections/festive.jpg') }}"
                        alt="Festive Specials"
                        loading="lazy"
                    >

                    <div class="tob-curated-card-content">
                        <span>CELEBRATE EVERY MOMENT</span>
                        <h3>Festive Specials</h3>
                        <p>
                            Make special occasions
                            a little more delicious.
                        </p>
                    </div>
                </article>

            </div>

        </div>
    </section>

    
{{-- SHOP BY MOOD --}}
<section class="tob-shop-mood" id="shop-by-mood">
    <div class="tob-collections-container">

        <div class="tob-collections-section-heading">
            <span class="tob-collections-eyebrow">
                FOLLOW YOUR CRAVINGS
            </span>

            <h2>Shop by Mood</h2>

            <p>
                Whatever you're craving today,
                there's a cookie waiting for you.
            </p>
        </div>

        <div class="tob-shop-mood-grid">

            {{-- CHOCOLATE LOVER --}}
            <a
                href="{{ route('shop', ['category' => 'chocolate']) }}"
                class="tob-shop-mood-card"
            >
                <img
                    src="{{ asset('images/collections/chocolate.jpg') }}"
                    alt="Chocolate cookies"
                    loading="lazy"
                >

                <div class="tob-shop-mood-overlay"></div>

                <div class="tob-shop-mood-content">
                    <span>FOR THE CHOCOLATE OBSESSED</span>
                    <h3>Chocolate Lover</h3>
                    <p>Rich, indulgent and irresistible.</p>
                    <strong>Explore Cookies <span aria-hidden="true">↗</span></strong>
                </div>
            </a>

            {{-- NUTTY CRAVINGS --}}
            <a
                href="{{ route('shop', ['category' => 'dry-fruit']) }}"
                class="tob-shop-mood-card"
            >
                <img
                    src="{{ asset('images/collections/dry-fruit.jpg') }}"
                    alt="Dry fruit cookies"
                    loading="lazy"
                >

                <div class="tob-shop-mood-overlay"></div>

                <div class="tob-shop-mood-content">
                    <span>FOR THE CRUNCH LOVERS</span>
                    <h3>Nutty Cravings</h3>
                    <p>Nutty goodness in every bite.</p>
                    <strong>Explore Cookies <span aria-hidden="true">↗</span></strong>
                </div>
            </a>

            {{-- CLASSIC COMFORT --}}
            <a
                href="{{ route('shop', ['category' => 'classic']) }}"
                class="tob-shop-mood-card"
            >
                <img
                    src="{{ asset('images/collections/classic.jpg') }}"
                    alt="Classic cookies"
                    loading="lazy"
                >

                <div class="tob-shop-mood-overlay"></div>

                <div class="tob-shop-mood-content">
                    <span>FOR THE TIMELESS TASTES</span>
                    <h3>Classic Comfort</h3>
                    <p>Familiar flavours, always loved.</p>
                    <strong>Explore Cookies <span aria-hidden="true">↗</span></strong>
                </div>
            </a>

        </div>

    </div>
</section>


    {{-- FINAL CTA --}}
    <section class="tob-collections-final-cta">
        <div class="tob-collections-container">

            <span class="tob-collections-eyebrow">
                FRESHLY BAKED HAPPINESS
            </span>

            <h2>
                Your Next Favourite Cookie
                Is Waiting.
            </h2>

            <p>
                Explore our complete range of delicious cookies.
            </p>

            <a href="{{ route('shop') }}" class="tob-collections-btn">
                Shop All Cookies
                <span aria-hidden="true">→</span>
            </a>

        </div>
    </section>

</main>

@endsection

@push('scripts')
    <script src="{{ asset('js/collections.js') }}" defer></script>
@endpush