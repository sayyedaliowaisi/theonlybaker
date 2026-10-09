@extends('layouts.app')


@section('title', 'My Wishlist | The Only Baker')


@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/wishlist.css') }}"
    >
@endpush


@section('content')

<section class="tob-wishlist-page">

    <div class="tob-wishlist-container">

        <nav
            class="tob-wishlist-breadcrumb"
            aria-label="Breadcrumb"
        >
            <a href="{{ route('home') }}">
                Home
            </a>

            <span>›</span>

            <a href="{{ route('account.dashboard') }}">
                My Account
            </a>

            <span>›</span>

            <span>
                Wishlist
            </span>
        </nav>


        <header class="tob-wishlist-header">

            <span class="tob-wishlist-eyebrow">
                SAVED WITH LOVE
            </span>

            <h1>
                My Wishlist
            </h1>

            <p>
                Your favourite treats,
                saved for later.
            </p>

        </header>


        @if($products->isNotEmpty())

            <div
                class="tob-wishlist-grid"
                id="tobWishlistGrid"
            >

                @foreach($products as $product)

                    <article
                        class="tob-wishlist-card"
                        data-wishlist-card
                        data-slug="{{ $product['slug'] }}"
                        data-name="{{ $product['name'] }}"
                        data-price="{{ $product['price'] }}"
                        data-image="{{ asset($product['image']) }}"
                    >

                        <div class="tob-wishlist-image">

                            <a
                                href="{{ route('product.show', $product['slug']) }}"
                            >
                                <img
                                    src="{{ asset($product['image']) }}"
                                    alt="{{ $product['name'] }}"
                                >
                            </a>


                            <button
                                type="button"
                                class="tob-wishlist-remove"
                                aria-label="Remove {{ $product['name'] }} from wishlist"
                                data-remove-wishlist
                                data-remove-url="{{ route('wishlist.remove', $product['slug']) }}"
                            >
                                ×
                            </button>

                        </div>


                        <div class="tob-wishlist-info">

                            <h2>
                                <a
                                    href="{{ route('product.show', $product['slug']) }}"
                                >
                                    {{ $product['name'] }}
                                </a>
                            </h2>


                            <div class="tob-wishlist-price">
                                ₹{{ $product['price'] }}
                            </div>


                            <button
                                type="button"
                                class="tob-wishlist-cart"
                                data-wishlist-add-cart
                            >
                                <svg
                                    viewBox="0 0 32 32"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M6 8h3l2 12h12l3-9H10"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                        stroke-linejoin="round"
                                    />

                                    <circle
                                        cx="13"
                                        cy="25"
                                        r="1.5"
                                        fill="currentColor"
                                    />

                                    <circle
                                        cx="23"
                                        cy="25"
                                        r="1.5"
                                        fill="currentColor"
                                    />
                                </svg>

                                <span>
                                    Add to Cart
                                </span>
                            </button>

                        </div>

                    </article>

                @endforeach

            </div>

        @endif


        <div
            class="tob-wishlist-empty"
            id="tobWishlistEmpty"
            @if($products->isNotEmpty())
                hidden
            @endif
        >

            <div class="tob-wishlist-empty-heart">
                ♡
            </div>

            <h2>
                Your Wishlist is Empty
            </h2>

            <p>
                Save the cookies you love
                and find them here anytime.
            </p>

            <a href="{{ route('shop') }}">
                Explore Cookies
            </a>

        </div>

    </div>

</section>

@endsection


@push('scripts')

<script src="{{ asset('js/wishlist-page.js') }}"></script>

@endpush