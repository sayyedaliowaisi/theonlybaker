{{-- =========================================================
    THE ONLY BAKER - HEADER
========================================================= --}}

<header class="tob-header">

    {{-- TOP BAR --}}
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


    {{-- MAIN NAVBAR --}}
    <nav class="tob-navbar">

        <div class="tob-navbar-inner">

            {{-- LOGO --}}
            {{-- LOGO WITH HERO DROP --}}
<div class="tob-logo-drop">

    <a href="{{ route('home') }}" class="tob-logo">
        <img
            src="{{ asset('images/logo/logo.png') }}"
            alt="The Only Baker"
        >
    </a>

</div>


            {{-- DESKTOP NAVIGATION --}}
            <div class="tob-menu">

                <a href="{{ route('home') }}" class="active">
                    Home
                </a>

                <a href="#">
                    Shop
                    <svg class="tob-arrow" viewBox="0 0 12 8">
                        <path d="M1 1.5L6 6.5L11 1.5"/>
                    </svg>
                </a>

                <a href="#">
                    Collections
                    <svg class="tob-arrow" viewBox="0 0 12 8">
                        <path d="M1 1.5L6 6.5L11 1.5"/>
                    </svg>
                </a>

                <a href="#">
                    Gifting
                    <svg class="tob-arrow" viewBox="0 0 12 8">
                        <path d="M1 1.5L6 6.5L11 1.5"/>
                    </svg>
                </a>

                <a href="#">Our Story</a>

                <a href="#">Contact</a>

            </div>


            {{-- RIGHT ACTIONS --}}
            <div class="tob-actions">

                {{-- SEARCH --}}
                <button type="button" aria-label="Search">
                    <svg viewBox="0 0 24 24">
                        <circle cx="10.8" cy="10.8" r="6.8"/>
                        <path d="M16 16L21 21"/>
                    </svg>
                </button>


                {{-- USER --}}
                <a href="#" aria-label="Account">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="3.7"/>
                        <path d="M5.5 20.5C6 16.8 8.4 14.5 12 14.5C15.6 14.5 18 16.8 18.5 20.5"/>
                    </svg>
                </a>


                {{-- WISHLIST --}}
                <a href="#" aria-label="Wishlist">
                    <svg viewBox="0 0 24 24">
                        <path d="M20.8 4.8C18.8 2.8 15.6 2.8 13.6 4.8L12 6.4L10.4 4.8C8.4 2.8 5.2 2.8 3.2 4.8C1.1 6.9 1.1 10.2 3.2 12.3L12 21L20.8 12.3C22.9 10.2 22.9 6.9 20.8 4.8Z"/>
                    </svg>
                </a>


                {{-- CART --}}
                <a href="#" class="tob-cart" aria-label="Cart">

                    <svg viewBox="0 0 24 24">
                        <path d="M6 8H18L17.2 20H6.8L6 8Z"/>
                        <path d="M9 8V5.8C9 4.1 10.3 3 12 3C13.7 3 15 4.1 15 5.8V8"/>
                    </svg>

                    <span class="tob-cart-count">0</span>

                </a>


                {{-- MOBILE HAMBURGER --}}
                <button
                    type="button"
                    class="tob-mobile-toggle"
                    id="tobMobileToggle"
                    aria-label="Open menu"
                    aria-expanded="false"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

            </div>

        </div>


        {{-- MOBILE MENU --}}
        <div class="tob-mobile-menu" id="tobMobileMenu">

            <a href="{{ route('home') }}">Home</a>
            <a href="#">Shop <span>+</span></a>
            <a href="#">Collections <span>+</span></a>
            <a href="#">Gifting <span>+</span></a>
            <a href="#">Our Story</a>
            <a href="#">Contact</a>

        </div>

    </nav>

</header>