{{-- =========================================================
    THE ONLY BAKER - HEADER
========================================================= --}}

@php
    $navbarWishlistCount = auth()->check()
        ? auth()->user()->wishlists()->count()
        : 0;
@endphp

<header class="tob-header">

    <div class="tob-topbar">
        <div class="tob-topbar-inner">

            <div class="tob-top-left">
                <span>Free Shipping on orders above ₹499</span>
                <span class="tob-dot">•</span>
                <span>Freshly Baked</span>
                <span class="tob-dot">•</span>
                <span>No Preservatives</span>
                <span class="tob-dot">•</span>
                <span>Pan India Delivery</span>
            </div>

            <div class="tob-top-right">
                <span>♡</span>
                <span>Make Someone Smile</span>
                <span>♡</span>
            </div>

        </div>
    </div>


    <nav class="tob-navbar">

        <div class="tob-navbar-inner">

            <div class="tob-logo-drop">

                <a
                    href="{{ route('home') }}"
                    class="tob-logo"
                    aria-label="The Only Baker Home"
                >
                    <img
                        src="{{ asset('images/logo/logo.png') }}"
                        alt="The Only Baker"
                    >
                </a>

            </div>


            <div class="tob-menu">

                <a
                    href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'active' : '' }}"
                >
                    Home
                </a>


                <div class="tob-nav-dropdown">

                    <a
                        href="{{ route('shop') }}"
                        class="tob-nav-dropdown-trigger {{ request()->routeIs('shop') || request()->routeIs('product.show') ? 'active' : '' }}"
                    >
                        Shop

                        <svg
                            class="tob-arrow"
                            viewBox="0 0 12 8"
                            aria-hidden="true"
                        >
                            <path d="M1 1.5L6 6.5L11 1.5"/>
                        </svg>
                    </a>


                    <div class="tob-shop-dropdown">

                        <div class="tob-shop-dropdown-inner">

                            <div class="tob-shop-dropdown-header">

                                <span>
                                    SHOP BY CATEGORY
                                </span>

                                <a href="{{ route('shop') }}">
                                    View All
                                    <span>→</span>
                                </a>

                            </div>


                            <div class="tob-shop-category-grid">

                                <a href="{{ route('shop', ['category' => 'cherry-fruit']) }}">
                                    Cherry Fruit
                                </a>

                                <a href="{{ route('shop', ['category' => 'badam-pista']) }}">
                                    Badam Pista
                                </a>

                                <a href="{{ route('shop', ['category' => 'american-chocolate-buddy']) }}">
                                    American Chocolate Buddy
                                </a>

                                <a href="{{ route('shop', ['category' => 'gem-badam-pista']) }}">
                                    Gem Badam Pista
                                </a>

                                <a href="{{ route('shop', ['category' => 'badam']) }}">
                                    Badam
                                </a>

                                <a href="{{ route('shop', ['category' => 'kaju-cashew']) }}">
                                    Kaju (Cashew)
                                </a>

                                <a href="{{ route('shop', ['category' => 'pista']) }}">
                                    Pista
                                </a>

                                <a href="{{ route('shop', ['category' => 'coconut']) }}">
                                    Coconut
                                </a>

                                <a href="{{ route('shop', ['category' => 'ajwain-long']) }}">
                                    Ajwain (Long)
                                </a>

                                <a href="{{ route('shop', ['category' => 'jeera']) }}">
                                    Jeera
                                </a>

                                <a href="{{ route('shop', ['category' => 'kaju-jeera']) }}">
                                    Kaju Jeera
                                </a>

                                <a href="{{ route('shop', ['category' => 'ajwain']) }}">
                                    Ajwain
                                </a>

                                <a href="{{ route('shop', ['category' => 'giri-coconut']) }}">
                                    Giri Coconut
                                </a>

                                <a href="{{ route('shop', ['category' => 'kala-jeera']) }}">
                                    Kala Jeera
                                </a>

                                <a href="{{ route('shop', ['category' => 'ajwain-short']) }}">
                                    Ajwain (Short)
                                </a>

                                <a href="{{ route('shop', ['category' => 'choco-chips']) }}">
                                    Choco Chips
                                </a>

                            </div>

                        </div>

                    </div>

                </div>


                <a href="#">
                    Collections

                    <svg class="tob-arrow" viewBox="0 0 12 8" aria-hidden="true">
                        <path d="M1 1.5L6 6.5L11 1.5"/>
                    </svg>
                </a>


                <a href="#">
                    Gifting

                    <svg class="tob-arrow" viewBox="0 0 12 8" aria-hidden="true">
                        <path d="M1 1.5L6 6.5L11 1.5"/>
                    </svg>
                </a>


                <a href="#">
                    Our Story
                </a>


                <a href="#">
                    Contact
                </a>

            </div>


            <div class="tob-actions">

                <button
                    type="button"
                    aria-label="Search"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.8" cy="10.8" r="6.8"/>
                        <path d="M16 16L21 21"/>
                    </svg>
                </button>


                <a
                    href="{{ auth()->check() ? route('account.dashboard') : route('login') }}"
                    aria-label="{{ auth()->check() ? 'My Account' : 'Login' }}"
                    title="{{ auth()->check() ? 'My Account' : 'Login' }}"
                    class="{{ request()->routeIs('account.*') || request()->routeIs('login') || request()->routeIs('register') ? 'active' : '' }}"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.7"/>
                        <path d="M5.5 20.5C6 16.8 8.4 14.5 12 14.5C15.6 14.5 18 16.8 18.5 20.5"/>
                    </svg>
                </a>


                <a
                    href="{{ auth()->check() ? route('wishlist.index') : route('login') }}"
                    aria-label="Wishlist"
                    title="Wishlist"
                    class="{{ request()->routeIs('wishlist.*') ? 'active' : '' }}"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M20.8 4.8C18.8 2.8 15.6 2.8 13.6 4.8L12 6.4L10.4 4.8C8.4 2.8 5.2 2.8 3.2 4.8C1.1 6.9 1.1 10.2 3.2 12.3L12 21L20.8 12.3C22.9 10.2 22.9 6.9 20.8 4.8Z"
                        />
                    </svg>

                    @auth
                        <span
                            class="tob-cart-count tob-wishlist-count {{ $navbarWishlistCount > 0 ? 'has-items' : '' }}"
                        >
                            {{ $navbarWishlistCount }}
                        </span>
                    @endauth
                </a>


                <a
                    href="{{ route('cart') }}"
                    class="tob-cart {{ request()->routeIs('cart') ? 'active' : '' }}"
                    aria-label="Cart"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 8H18L17.2 20H6.8L6 8Z"/>
                        <path d="M9 8V5.8C9 4.1 10.3 3 12 3C13.7 3 15 4.1 15 5.8V8"/>
                    </svg>

                    <span class="tob-cart-count">
                        0
                    </span>
                </a>


                <button
                    type="button"
                    class="tob-mobile-toggle"
                    id="tobMobileToggle"
                    aria-label="Open menu"
                    aria-expanded="false"
                    aria-controls="tobMobileMenu"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

            </div>

        </div>


        <div
            class="tob-mobile-menu"
            id="tobMobileMenu"
        >

            <a href="{{ route('home') }}">
                Home
            </a>


            <div class="tob-mobile-shop">

                <div class="tob-mobile-shop-row">

                    <a
                        href="{{ route('shop') }}"
                        class="tob-mobile-shop-link"
                    >
                        Shop
                    </a>

                    <button
                        type="button"
                        class="tob-mobile-shop-toggle"
                        id="tobMobileShopToggle"
                        aria-label="Open Shop Categories"
                        aria-expanded="false"
                        aria-controls="tobMobileShopCategories"
                    >
                        <span></span>
                        <span></span>
                    </button>

                </div>


                <div
                    class="tob-mobile-shop-categories"
                    id="tobMobileShopCategories"
                >

                    <a
                        href="{{ route('shop') }}"
                        class="tob-mobile-view-all"
                    >
                        <span>All Products</span>
                        <span>→</span>
                    </a>

                    <a href="{{ route('shop', ['category' => 'cherry-fruit']) }}">
                        Cherry Fruit
                    </a>

                    <a href="{{ route('shop', ['category' => 'badam-pista']) }}">
                        Badam Pista
                    </a>

                    <a href="{{ route('shop', ['category' => 'american-chocolate-buddy']) }}">
                        American Chocolate Buddy
                    </a>

                    <a href="{{ route('shop', ['category' => 'gem-badam-pista']) }}">
                        Gem Badam Pista
                    </a>

                    <a href="{{ route('shop', ['category' => 'badam']) }}">
                        Badam
                    </a>

                    <a href="{{ route('shop', ['category' => 'kaju-cashew']) }}">
                        Kaju (Cashew)
                    </a>

                    <a href="{{ route('shop', ['category' => 'pista']) }}">
                        Pista
                    </a>

                    <a href="{{ route('shop', ['category' => 'coconut']) }}">
                        Coconut
                    </a>

                    <a href="{{ route('shop', ['category' => 'ajwain-long']) }}">
                        Ajwain (Long)
                    </a>

                    <a href="{{ route('shop', ['category' => 'jeera']) }}">
                        Jeera
                    </a>

                    <a href="{{ route('shop', ['category' => 'kaju-jeera']) }}">
                        Kaju Jeera
                    </a>

                    <a href="{{ route('shop', ['category' => 'ajwain']) }}">
                        Ajwain
                    </a>

                    <a href="{{ route('shop', ['category' => 'giri-coconut']) }}">
                        Giri Coconut
                    </a>

                    <a href="{{ route('shop', ['category' => 'kala-jeera']) }}">
                        Kala Jeera
                    </a>

                    <a href="{{ route('shop', ['category' => 'ajwain-short']) }}">
                        Ajwain (Short)
                    </a>

                    <a href="{{ route('shop', ['category' => 'choco-chips']) }}">
                        Choco Chips
                    </a>

                </div>

            </div>


            <a href="#">
                Collections
                <span>+</span>
            </a>

            <a href="#">
                Gifting
                <span>+</span>
            </a>

            <a href="#">
                Our Story
            </a>

            <a href="#">
                Contact
            </a>


            @auth

                <a href="{{ route('wishlist.index') }}">
                    My Wishlist
                    <span>{{ $navbarWishlistCount }}</span>
                </a>

                <a href="{{ route('account.dashboard') }}">
                    My Account
                </a>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="tob-mobile-logout-form"
                >
                    @csrf

                    <button
                        type="submit"
                        class="tob-mobile-logout"
                    >
                        Logout
                    </button>
                </form>

            @else

                <a href="{{ route('login') }}">
                    Login
                </a>

                <a href="{{ route('register') }}">
                    Create Account
                </a>

            @endauth

        </div>

    </nav>

</header>