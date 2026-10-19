@extends('layouts.app')

@section('title', 'Your Cart | The Only Baker')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <style>
        .tob-cart-stock-alert {
            margin: 0 0 18px;
            padding: 13px 16px;
            border: 1px solid #e6c7ba;
            border-radius: 8px;
            background: #fff8f4;
            color: #8a4534;
            font-size: 13px;
            line-height: 1.6;
        }

        .tob-cart-stock-alert[hidden],
        .tob-cart-stock-note[hidden] {
            display: none !important;
        }

        .tob-cart-stock-note {
            margin-top: 8px;
            font-size: 12px;
            line-height: 1.5;
            color: #a24a3b;
        }

        .tob-cart-quantity button:disabled {
            opacity: .35;
            cursor: not-allowed;
        }

        .tob-checkout-button.tob-checkout-disabled {
            opacity: .5;
            cursor: not-allowed;
        }
    </style>
@endpush

@section('content')

<div
    class="tob-cart-page"
    id="tobCartPage"
    data-stock-url="{{ route('cart.check-stock') }}"
    data-customer-authenticated="{{ auth()->check() ? '1' : '0' }}"
    data-checkout-url="{{ route('checkout') }}"
>
    {{-- BREADCRUMB --}}
    <div class="tob-cart-breadcrumb-wrap">
        <div class="tob-cart-container">
            <nav class="tob-cart-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>›</span>
                <span>Cart</span>
            </nav>
        </div>
    </div>

    {{-- CART --}}
    <section class="tob-cart-section">
        <div class="tob-cart-container">

            <div class="tob-cart-heading">
                <span class="tob-cart-eyebrow">
                    YOUR SELECTION
                </span>

                <h1>Your Cart</h1>

                <p id="tobCartHeadingText">
                    Your delicious picks are waiting for you.
                </p>
            </div>

            {{-- FILLED CART --}}
            <div
                class="tob-cart-layout"
                id="tobFilledCart"
                hidden
            >
                {{-- LEFT --}}
                <div class="tob-cart-products">

                    <div
                        id="tobCartStockAlert"
                        class="tob-cart-stock-alert"
                        role="alert"
                        hidden
                    ></div>

                    <div class="tob-cart-products-header">
                        <span>Product</span>
                        <div>
                            <span>Price</span>
                            <span>Quantity</span>
                            <span>Total</span>
                        </div>
                    </div>

                    <div id="tobCartItems"></div>

                    <div class="tob-cart-bottom-actions">
                        <a
                            href="{{ route('shop') }}"
                            class="tob-continue-shopping"
                        >
                            <span>←</span>
                            Continue Shopping
                        </a>

                        <button
                            type="button"
                            class="tob-clear-cart"
                            id="tobClearCart"
                        >
                            Clear Cart
                        </button>
                    </div>
                </div>

                {{-- ORDER SUMMARY --}}
                <aside class="tob-order-summary">

                    <div class="tob-summary-heading">
                        <span class="tob-summary-icon">
                            <svg viewBox="0 0 32 32" aria-hidden="true">
                                <path
                                    d="M7 9h18v18H7V9Z"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.4"
                                />
                                <path
                                    d="M11 9V7c0-3 2-5 5-5s5 2 5 5v2"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.4"
                                />
                            </svg>
                        </span>

                        <div>
                            <h2>Order Summary</h2>
                            <p>Your delicious order</p>
                        </div>
                    </div>

                    <div class="tob-summary-lines">
                        <div class="tob-summary-line">
                            <span>Subtotal</span>
                            <strong id="tobCartSubtotal">₹0</strong>
                        </div>

                        <div class="tob-summary-line">
                            <span>Shipping</span>
                            <strong
                                class="tob-free-shipping"
                                id="tobCartShipping"
                            >
                                FREE
                            </strong>
                        </div>
                    </div>

                    <div
                        class="tob-shipping-message"
                        id="tobShippingMessage"
                    >
                        Free shipping on orders above ₹499
                    </div>

                    <div class="tob-summary-total">
                        <div>
                            <span>Total</span>
                            <small>Inclusive of all taxes</small>
                        </div>

                        <strong id="tobCartTotal">
                            ₹0
                        </strong>
                    </div>

                    

                    <a
                        href="{{ route('checkout') }}"
                        class="tob-checkout-button"
                        id="tobCheckoutButton"
                    >
                        <span>Proceed to Checkout</span>
                        <span class="tob-checkout-arrow">→</span>
                    </a>

                    <div class="tob-secure-payment">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <rect
                                x="5"
                                y="10"
                                width="14"
                                height="10"
                                rx="2"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />
                            <path
                                d="M8 10V7a4 4 0 0 1 8 0v3"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />
                        </svg>

                        <span>Secure &amp; Safe Checkout</span>
                    </div>
                </aside>
            </div>

            {{-- EMPTY CART --}}
            <div
                class="tob-empty-cart"
                id="tobEmptyCart"
                hidden
            >
                <div class="tob-empty-cart-icon">
                    <svg viewBox="0 0 80 80">
                        <path
                            d="M18 22h44l-3 43H21L18 22Z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        />
                        <path
                            d="M29 22v-5c0-7 5-12 11-12s11 5 11 12v5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        />
                    </svg>
                </div>

                <span class="tob-empty-eyebrow">
                    YOUR CART IS EMPTY
                </span>

                <h2>
                    Your Cookie Box<br>
                    Feels a Little Light
                </h2>

                <p>
                    Add some freshly baked happiness to your cart
                    and make every bite a little sweeter.
                </p>

                <a
                    href="{{ route('shop') }}"
                    class="tob-empty-shop-button"
                >
                    Explore Our Cookies
                    <span>→</span>
                </a>
            </div>
        </div>
    </section>

    {{-- BENEFITS --}}
    <section class="tob-cart-benefits">
        <div class="tob-cart-container">
            <div class="tob-cart-benefits-grid">

                <div class="tob-cart-benefit">
                    <strong>Freshly Baked</strong>
                    <span>Made with care</span>
                </div>

                <div class="tob-cart-benefit">
                    <strong>No Preservatives</strong>
                    <span>Just real ingredients</span>
                </div>

                <div class="tob-cart-benefit">
                    <strong>Secure Packaging</strong>
                    <span>Freshness guaranteed</span>
                </div>

                <div class="tob-cart-benefit">
                    <strong>Pan India Delivery</strong>
                    <span>Happiness delivered</span>
                </div>

            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/cart.js') }}?v=20261019-2" defer></script>
@endpush