@extends('layouts.app')

@section('title', 'Order Confirmed | The Only Baker')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/order-success.css') }}">
@endpush


@section('content')

<section class="tob-success-page">

    <div class="tob-success-container">

        <div class="tob-success-card">

            <div class="tob-success-icon">

                <svg viewBox="0 0 64 64">

                    <circle
                        cx="32"
                        cy="32"
                        r="29"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    />

                    <path
                        d="M19 32L28 41L46 22"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="3"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />

                </svg>

            </div>


            <span class="tob-success-eyebrow">
                ORDER CONFIRMED
            </span>


            <h1>
                Thank You, {{ $order->name }}!
            </h1>


            <p class="tob-success-intro">
                Your freshly baked happiness is officially
                on its way.
            </p>


            <div class="tob-success-order-number">

                <span>
                    Order Number
                </span>

                <strong>
                    {{ $order->order_number }}
                </strong>

            </div>


            <div class="tob-success-details">

                <div>

                    <span>
                        {{ is_null($order->shipping) ? 'Subtotal' : 'Total' }}
                    </span>

                    <strong>
                        ₹{{ number_format($order->total, 0) }}
                    </strong>

                </div>


                <div>

                    <span>
                        Shipping
                    </span>

                    <strong>

                        @if(is_null($order->shipping))

                            Calculated separately

                        @elseif((float) $order->shipping === 0.0)

                            FREE

                        @else

                            ₹{{ number_format($order->shipping, 0) }}

                        @endif

                    </strong>

                </div>


                <div>

                    <span>
                        Payment
                    </span>

                    <strong>
                        Cash on Delivery
                    </strong>

                </div>


                <div>

                    <span>
                        Deliver To
                    </span>

                    <strong>
                        {{ $order->city }},
                        {{ $order->state }}
                    </strong>

                </div>

            </div>


            @if(is_null($order->shipping))

                <p
                    style="
                        margin:15px 0 0;
                        color:#806d61;
                        font-size:11px;
                        line-height:1.6;
                        text-align:center;
                    "
                >
                    Shipping charges, if applicable,
                    will be confirmed separately.
                </p>

            @endif


            <div class="tob-success-message">

                <strong>
                    What happens next?
                </strong>

                <p>
                    We'll prepare your order with care and
                    keep you updated using the contact
                    details you provided.
                </p>

            </div>


            <a
                href="{{ route('shop') }}"
                class="tob-success-shop"
            >

                Continue Shopping

                <span>
                    →
                </span>

            </a>

        </div>

    </div>

</section>

@endsection


@push('scripts')

<script>
document.addEventListener("DOMContentLoaded", function () {

    if (window.TheOnlyBakerCart) {

        window.TheOnlyBakerCart.clearCart();

    }

});
</script>

@endpush