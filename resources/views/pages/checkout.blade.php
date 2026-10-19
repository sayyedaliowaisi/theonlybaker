
@extends('layouts.app')

@section('title', 'Checkout | The Only Baker')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout-addresses.css') }}?v=3">
@endpush

@section('content')
<div class="tob-checkout-page"
     data-stock-url="{{ route('cart.check-stock') }}"
     data-checkout-url="{{ route('checkout.store') }}">

    {{-- BREADCRUMB --}}
    <div class="tob-checkout-breadcrumb-wrap">
        <div class="tob-checkout-container">
            <nav class="tob-checkout-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>›</span>
                <a href="{{ route('cart') }}">Cart</a>
                <span>›</span>
                <span>Checkout</span>
            </nav>
        </div>
    </div>

    <section class="tob-checkout-section">
        <div class="tob-checkout-container">
            <div class="tob-checkout-heading">
                <span>ALMOST THERE</span>
                <h1>Checkout</h1>
                <p>Freshly baked happiness is just a few steps away.</p>
            </div>

            <div class="tob-checkout-empty" id="tobCheckoutEmpty" hidden>
                <h2>Your cart is empty</h2>
                <p>Add your favourite cookies before continuing to checkout.</p>
                <a href="{{ route('shop') }}">Explore Our Cookies <span>→</span></a>
            </div>

            <div class="tob-checkout-layout" id="tobCheckoutLayout" hidden>
                <div class="tob-checkout-main">
                    <form class="tob-checkout-form" id="tobCheckoutForm" method="POST" action="{{ route('checkout.store') }}">
                        @csrf
                        <input type="hidden" name="checkout_token" id="tobCheckoutToken" value="{{ $checkoutToken }}">
                        <div id="tobCheckoutStockWarning" class="tob-checkout-error" role="alert" aria-live="polite" hidden></div>

                        {{-- CONTACT INFORMATION --}}
                        <section class="tob-checkout-box">
                            <div class="tob-checkout-box-heading">
                                <span class="tob-checkout-step">01</span>
                                <div>
                                    <h2>Contact Information</h2>
                                    <p>We'll use this to keep you updated about your order.</p>
                                </div>
                            </div>
                            <div class="tob-form-grid">
                                <div class="tob-form-group">
                                    <label for="checkoutName">Full Name <span>*</span></label>
                                    <input type="text" id="checkoutName" name="name" value="{{ old('name', $user?->name) }}" placeholder="Enter your full name" autocomplete="name" maxlength="100" required>
                                </div>
                                <div class="tob-form-group">
                                    <label for="checkoutPhone">Phone Number <span>*</span></label>
                                    <input type="tel" id="checkoutPhone" name="phone" value="{{ old('phone', $user?->phone) }}" placeholder="+91 98765 43210" autocomplete="tel" maxlength="20" required>
                                </div>
                                <div class="tob-form-group tob-form-full">
                                    <label for="checkoutEmail">Email Address <span>*</span></label>
                                    <input type="email" id="checkoutEmail" name="email" value="{{ old('email', $user?->email) }}" placeholder="you@example.com" autocomplete="email" maxlength="150" required>
                                </div>
                            </div>
                        </section>

                        {{-- DELIVERY ADDRESS --}}
                        <section class="tob-checkout-box">
                            <div class="tob-checkout-box-heading">
                                <span class="tob-checkout-step">02</span>
                                <div>
                                    <h2>Delivery Address</h2>
                                    <p>Where should we deliver your freshly baked treats?</p>
                                </div>
                            </div>

                            @include('pages.partials.saved-addresses')

                            <div class="tob-form-grid">
                                <div class="tob-form-group tob-form-full">
                                    <label for="checkoutAddress">Address <span>*</span></label>
                                    <input type="text" id="checkoutAddress" name="address" value="{{ old('address') }}" placeholder="House / Flat No., Building, Street" autocomplete="street-address" maxlength="500" required>
                                </div>
                                <div class="tob-form-group tob-form-full">
                                    <label for="checkoutLandmark">Landmark</label>
                                    <input type="text" id="checkoutLandmark" name="landmark" value="{{ old('landmark') }}" placeholder="Nearby landmark (optional)" maxlength="150">
                                </div>
                                <div class="tob-form-group">
                                    <label for="checkoutCity">City <span>*</span></label>
                                    <input type="text" id="checkoutCity" name="city" value="{{ old('city') }}" placeholder="City" autocomplete="address-level2" maxlength="100" required>
                                </div>
                                <div class="tob-form-group">
                                    <label for="checkoutState">State <span>*</span></label>
                                    <input type="text" id="checkoutState" name="state" value="{{ old('state') }}" placeholder="State" autocomplete="address-level1" maxlength="100" required>
                                </div>
                                <div class="tob-form-group">
                                    <label for="checkoutPincode">PIN Code <span>*</span></label>
                                    <input type="text" id="checkoutPincode" name="pincode" value="{{ old('pincode') }}" placeholder="6-digit PIN code" maxlength="6" minlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="postal-code" required>
                                </div>
                                <div class="tob-form-group">
                                    <label for="checkoutCountry">Country</label>
                                    <input type="text" id="checkoutCountry" value="India" readonly>
                                </div>
                            </div>
                        </section>

                        {{-- PAYMENT METHOD --}}
                        <section class="tob-checkout-box">
                            <div class="tob-checkout-box-heading">
                                <span class="tob-checkout-step">03</span>
                                <div>
                                    <h2>Payment Method</h2>
                                    <p>Choose how you'd like to pay.</p>
                                </div>
                            </div>
                            <div class="tob-payment-methods">
                                <label class="tob-payment-option">
                                    <input type="radio" name="payment_method" value="cod" checked required>
                                    <span class="tob-payment-radio"></span>
                                    <span class="tob-payment-content">
                                        <strong>Cash on Delivery</strong>
                                        <small>Pay when your order arrives</small>
                                    </span>
                                </label>
                            </div>
                        </section>

                        <button type="submit" class="tob-place-order tob-place-order-mobile">
                            <span>Place Order</span><span>→</span>
                        </button>
                    </form>
                </div>

                {{-- ORDER SUMMARY --}}
                <aside class="tob-checkout-summary">
                    <div class="tob-checkout-summary-heading">
                        <div><h2>Your Order</h2><p id="tobCheckoutItemCount">0 items</p></div>
                        <a href="{{ route('cart') }}">Edit Cart</a>
                    </div>
                    <div class="tob-checkout-products" id="tobCheckoutProducts"></div>
                    <div class="tob-checkout-totals">
                        <div><span>Subtotal</span><strong id="tobCheckoutSubtotal">₹0</strong></div>
                        <div><span>Shipping</span><strong id="tobCheckoutShipping">Calculated at checkout</strong></div>
                    </div>
                    <div class="tob-checkout-shipping-note" id="tobCheckoutShippingNote">Free shipping on orders above ₹499</div>
                    <div class="tob-checkout-grand-total">
                        <div>
                            <span>Order Subtotal</span>
                            <small>Shipping, if applicable, is calculated separately</small>
                        </div>
                        <strong id="tobCheckoutTotal">₹0</strong>
                    </div>
                    <button type="submit" form="tobCheckoutForm" class="tob-place-order tob-place-order-desktop">
                        <span>Place Order</span><span>→</span>
                    </button>
                    <div class="tob-checkout-secure">Secure &amp; Safe Checkout</div>
                </aside>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/checkout.js') }}"></script>
    <script src="{{ asset('js/checkout-addresses.js') }}?v=3"></script>
@endpush
