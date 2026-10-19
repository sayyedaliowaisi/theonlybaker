{{-- THE ONLY BAKER - HEADER --}}
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
                <span>♡</span><span>Make Someone Smile</span><span>♡</span>
            </div>
        </div>
    </div>

    <nav class="tob-navbar" aria-label="Main navigation">
        <div class="tob-navbar-inner">
            <button type="button" class="tob-mobile-toggle" id="tobMobileToggle"
                    aria-label="Open menu" aria-expanded="false" aria-controls="tobMobileMenu">
                <span></span><span></span><span></span>
            </button>

            <div class="tob-logo-drop">
                <a href="{{ route('home') }}" class="tob-logo" aria-label="The Only Baker Home">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="The Only Baker">
                </a>
            </div>

            <div class="tob-menu">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>

                <div class="tob-nav-dropdown" id="tobDesktopShopDropdown">
                    <button type="button"
                            class="tob-nav-dropdown-trigger {{ request()->routeIs('shop', 'product.show') ? 'active' : '' }}"
                            id="tobDesktopShopToggle" aria-expanded="false"
                            aria-controls="tobDesktopShopCategories">
                        Shop
                        <svg class="tob-arrow" viewBox="0 0 12 8" aria-hidden="true">
                            <path d="M1 1.5L6 6.5L11 1.5"/>
                        </svg>
                    </button>
                    <div class="tob-shop-dropdown" id="tobDesktopShopCategories">
                        <div class="tob-shop-dropdown-inner">
                            <div class="tob-shop-dropdown-header">
                                <span>SHOP BY CATEGORY</span>
                                <a href="{{ route('shop') }}">View All <span>→</span></a>
                            </div>
                            <div class="tob-shop-category-grid">
                                <a href="{{ route('shop', ['category' => 'dry-fruit']) }}">Dry Fruit Cookies</a>
                                <a href="{{ route('shop', ['category' => 'chocolate']) }}">Chocolate Cookies</a>
                                <a href="{{ route('shop', ['category' => 'classic']) }}">Classic Cookies</a>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('collections') }}" class="{{ request()->routeIs('collections') ? 'active' : '' }}">Collections</a>
                <a href="{{ route('gifting') }}" class="{{ request()->routeIs('gifting') ? 'active' : '' }}">Gifting</a>
                <a href="{{ route('custom.orders') }}" class="{{ request()->routeIs('custom.orders') ? 'active' : '' }}">Custom Orders</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Our Story</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            </div>

            <div class="tob-actions">
                <button type="button" aria-label="Search" data-tob-search-open>
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.8" cy="10.8" r="6.8"/><path d="M16 16L21 21"/>
                    </svg>
                </button>

                <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}"
                   aria-label="{{ auth()->check() ? 'My Account' : 'Login' }}"
                   title="{{ auth()->check() ? 'My Account' : 'Login' }}"
                   class="{{ request()->routeIs('account.*', 'login', 'register') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.7"/>
                        <path d="M5.5 20.5C6 16.8 8.4 14.5 12 14.5C15.6 14.5 18 16.8 18.5 20.5"/>
                    </svg>
                </a>

                <a href="{{ auth()->check() ? route('wishlist.index') : route('login') }}"
                   aria-label="Wishlist" title="Wishlist"
                   class="{{ request()->routeIs('wishlist.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M20.8 4.8C18.8 2.8 15.6 2.8 13.6 4.8L12 6.4L10.4 4.8C8.4 2.8 5.2 2.8 3.2 4.8C1.1 6.9 1.1 10.2 3.2 12.3L12 21L20.8 12.3C22.9 10.2 22.9 6.9 20.8 4.8Z"/>
                    </svg>
                    @auth
                        <span class="tob-cart-count tob-wishlist-count {{ $navbarWishlistCount > 0 ? 'has-items' : '' }}">{{ $navbarWishlistCount }}</span>
                    @endauth
                </a>

                <a href="{{ route('cart') }}" class="tob-cart {{ request()->routeIs('cart') ? 'active' : '' }}" aria-label="Cart">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 8H18L17.2 20H6.8L6 8Z"/>
                        <path d="M9 8V5.8C9 4.1 10.3 3 12 3C13.7 3 15 4.1 15 5.8V8"/>
                    </svg>
                    <span class="tob-cart-count">0</span>
                </a>
            </div>
        </div>

        <div class="tob-mobile-menu" id="tobMobileMenu">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>

            <button type="button" class="tob-mobile-search-link" data-tob-search-open>
                Search Products <span>⌕</span>
            </button>

            <a href="{{ auth()->check() ? route('wishlist.index') : route('login') }}">
                My Wishlist
                @auth <span>{{ $navbarWishlistCount }}</span> @endauth
            </a>

            <div class="tob-mobile-shop">
                <div class="tob-mobile-shop-row">
                    <button type="button" class="tob-mobile-shop-link tob-mobile-shop-button"
                            id="tobMobileShopToggle" aria-expanded="false"
                            aria-controls="tobMobileShopCategories" aria-label="Open Shop Categories">
                        <span>Shop</span>
                        <span class="tob-mobile-shop-toggle" aria-hidden="true"><span></span><span></span></span>
                    </button>
                </div>
                <div class="tob-mobile-shop-categories" id="tobMobileShopCategories">
                    <div class="tob-mobile-view-all"><span>Categories</span></div>
                    <a href="{{ route('shop') }}">Shop All</a>
                    <a href="{{ route('shop', ['category' => 'dry-fruit']) }}">Dry Fruit Cookies</a>
                    <a href="{{ route('shop', ['category' => 'chocolate']) }}">Chocolate Cookies</a>
                    <a href="{{ route('shop', ['category' => 'classic']) }}">Classic Cookies</a>
                </div>
            </div>

            <a href="{{ route('collections') }}" class="{{ request()->routeIs('collections') ? 'active' : '' }}">Collections</a>
            <a href="{{ route('gifting') }}" class="{{ request()->routeIs('gifting') ? 'active' : '' }}">Gifting</a>
            <a href="{{ route('custom.orders') }}" class="{{ request()->routeIs('custom.orders') ? 'active' : '' }}">Custom Orders</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Our Story</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            @auth
    <a
        href="{{ route('orders.track') }}"
        class="{{ request()->routeIs('orders.track*') ? 'active' : '' }}"
    >
        Track Your Order
    </a>
@endauth
            <a href="{{ route('retailer.register') }}">Become a Retailer <span>→</span></a>

            @auth
                <a href="{{ route('account.dashboard') }}">My Account</a>
                <form method="POST" action="{{ route('logout') }}" class="tob-mobile-logout-form">
                    @csrf
                    <button type="submit" class="tob-mobile-logout">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Create Account</a>
            @endauth
        </div>
    </nav>
</header>
