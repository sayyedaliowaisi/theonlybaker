@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' | The Only Baker')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-detail.css') }}">
@endpush


@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Order Progress
    |--------------------------------------------------------------------------
    */

    $orderSteps = [
        'pending' => 1,
        'confirmed' => 2,
        'shipped' => 3,
        'delivered' => 4,
    ];

    $currentStep = $orderSteps[$order->order_status] ?? 1;

    $isCancelled = $order->order_status === 'cancelled';

    $products = config('products', []);
@endphp


<section class="tob-account-page">

    <div class="tob-account-container">

        {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}
        <div class="tob-account-breadcrumb">

            <a href="{{ route('home') }}">
                Home
            </a>

            <span>›</span>

            <a href="{{ route('account.dashboard') }}">
                My Account
            </a>

            <span>›</span>

            <a href="{{ route('account.orders') }}">
                My Orders
            </a>

            <span>›</span>

            Order #{{ $order->order_number }}

        </div>


        {{-- =====================================================
             PAGE HEADING
        ====================================================== --}}
        <div class="tob-order-detail-heading">

            <div>

                <p>
                    ORDER DETAILS
                </p>

                <h1>
                    Order #{{ $order->order_number }}
                </h1>

                <span>
                    Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                </span>

            </div>


            <span
                class="
                    tob-order-status
                    tob-status-{{ $order->order_status }}
                "
            >
                {{ ucfirst($order->order_status) }}
            </span>

        </div>


        {{-- =====================================================
             MAIN ACCOUNT GRID
        ====================================================== --}}
        <div class="tob-dashboard-grid">

            {{-- =================================================
                 SIDEBAR
            ================================================== --}}
            <aside class="tob-account-sidebar">

                <div class="tob-account-user">

                    <strong>
                        {{ auth()->user()->name }}
                    </strong>

                    <span>
                        {{ auth()->user()->email }}
                    </span>

                </div>


                <nav class="tob-account-nav">

                    <a href="{{ route('account.dashboard') }}">
                        Dashboard
                    </a>


                    <a
                        href="{{ route('account.orders') }}"
                        class="active"
                    >
                        My Orders
                    </a>


                    <a
                        href="{{ route('wishlist.index') }}"
                        class="{{ request()->routeIs('wishlist.*') ? 'active' : '' }}"
                    >
                        My Wishlist
                    </a>


                    <a href="{{ route('account.profile') }}">
                        Account Details
                    </a>


                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >
                        @csrf

                        <button type="submit">
                            Logout
                        </button>

                    </form>

                </nav>

            </aside>


            {{-- =================================================
                 ORDER CONTENT
            ================================================== --}}
            <div class="tob-dashboard-content">

                {{-- =============================================
                     ORDER TRACKING
                ============================================== --}}
                <section class="tob-order-detail-card">

                    <div class="tob-order-detail-card-heading">

                        <div>

                            <span>
                                ORDER STATUS
                            </span>

                            <h2>
                                Track Your Order
                            </h2>

                        </div>

                    </div>


                    @if($isCancelled)

                        <div class="tob-order-cancelled">

                            <div class="tob-order-cancelled-icon">
                                ×
                            </div>

                            <div>

                                <strong>
                                    Order Cancelled
                                </strong>

                                <p>
                                    This order has been cancelled.
                                </p>

                            </div>

                        </div>

                    @else

                        <div class="tob-order-tracker">

                            {{-- ORDER PLACED --}}
                            <div
                                class="
                                    tob-order-track-step
                                    {{ $currentStep >= 1 ? 'completed' : '' }}
                                "
                            >

                                <div class="tob-order-track-marker">
                                    ✓
                                </div>

                                <div class="tob-order-track-text">

                                    <strong>
                                        Order Placed
                                    </strong>

                                    <span>
                                        {{ $order->created_at->format('d M Y') }}
                                    </span>

                                </div>

                            </div>


                            {{-- CONFIRMED --}}
                            <div
                                class="
                                    tob-order-track-step
                                    {{ $currentStep >= 2 ? 'completed' : '' }}
                                "
                            >

                                <div class="tob-order-track-marker">
                                    {{ $currentStep >= 2 ? '✓' : '2' }}
                                </div>

                                <div class="tob-order-track-text">

                                    <strong>
                                        Confirmed
                                    </strong>

                                    <span>
                                        {{ $currentStep >= 2
                                            ? 'Your order has been confirmed'
                                            : 'Waiting for confirmation'
                                        }}
                                    </span>

                                </div>

                            </div>


                            {{-- SHIPPED --}}
                            <div
                                class="
                                    tob-order-track-step
                                    {{ $currentStep >= 3 ? 'completed' : '' }}
                                "
                            >

                                <div class="tob-order-track-marker">
                                    {{ $currentStep >= 3 ? '✓' : '3' }}
                                </div>

                                <div class="tob-order-track-text">

                                    <strong>
                                        Shipped
                                    </strong>

                                    <span>
                                        {{ $currentStep >= 3
                                            ? 'Your order is on the way'
                                            : 'Preparing for shipment'
                                        }}
                                    </span>

                                </div>

                            </div>


                            {{-- DELIVERED --}}
                            <div
                                class="
                                    tob-order-track-step
                                    {{ $currentStep >= 4 ? 'completed' : '' }}
                                "
                            >

                                <div class="tob-order-track-marker">
                                    {{ $currentStep >= 4 ? '✓' : '4' }}
                                </div>

                                <div class="tob-order-track-text">

                                    <strong>
                                        Delivered
                                    </strong>

                                    <span>
                                        {{ $currentStep >= 4
                                            ? 'Your order has been delivered'
                                            : 'Awaiting delivery'
                                        }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    @endif

                </section>


                {{-- =============================================
                     ORDER ITEMS
                ============================================== --}}
                <section class="tob-order-detail-card">

                    <div class="tob-order-detail-card-heading">

                        <div>

                            <span>
                                YOUR ORDER
                            </span>

                            <h2>
                                Order Items
                            </h2>

                        </div>

                        <strong class="tob-order-item-count">

                            {{ $order->items->sum('quantity') }}

                            {{ \Illuminate\Support\Str::plural(
                                'Item',
                                $order->items->sum('quantity')
                            ) }}

                        </strong>

                    </div>


                    <div class="tob-order-detail-items">

                        @foreach($order->items as $item)

                            @php
                                $productExists =
                                    !empty($item->product_slug) &&
                                    isset($products[$item->product_slug]);
                            @endphp


                            <article class="tob-order-detail-item">

                                <div class="tob-order-detail-product-image">

                                    @if($productExists)

                                        <a
                                            href="{{ route(
                                                'product.show',
                                                $item->product_slug
                                            ) }}"
                                        >

                                            <img
                                                src="{{ asset(
                                                    $products[$item->product_slug]['image']
                                                ) }}"
                                                alt="{{ $item->product_name }}"
                                            >

                                        </a>

                                    @elseif($item->image)

                                        <img
                                            src="{{ asset($item->image) }}"
                                            alt="{{ $item->product_name }}"
                                        >

                                    @else

                                        <div class="tob-order-image-placeholder">
                                            TOB
                                        </div>

                                    @endif

                                </div>


                                <div class="tob-order-detail-product-info">

                                    <span>
                                        THE ONLY BAKER
                                    </span>


                                    <h3>

                                        @if($productExists)

                                            <a
                                                href="{{ route(
                                                    'product.show',
                                                    $item->product_slug
                                                ) }}"
                                            >
                                                {{ $item->product_name }}
                                            </a>

                                        @else

                                            {{ $item->product_name }}

                                        @endif

                                    </h3>


                                    <div class="tob-order-product-meta">

                                        <span>
                                            ₹{{ number_format(
                                                (float) $item->price,
                                                2
                                            ) }}
                                        </span>

                                        <span>
                                            ×
                                        </span>

                                        <span>
                                            Qty {{ $item->quantity }}
                                        </span>

                                    </div>


                                    @if(
                                        $order->order_status === 'delivered' &&
                                        $productExists
                                    )

                                        <a
                                            href="{{ route(
                                                'product.show',
                                                $item->product_slug
                                            ) }}#reviews"
                                            class="tob-order-review-product"
                                        >
                                            REVIEW PRODUCT
                                        </a>

                                    @endif

                                </div>


                                <div class="tob-order-detail-item-total">

                                    <span>
                                        Total
                                    </span>

                                    <strong>
                                        ₹{{ number_format(
                                            (float) $item->total,
                                            2
                                        ) }}
                                    </strong>

                                </div>

                            </article>

                        @endforeach

                    </div>

                </section>


                {{-- =============================================
                     CUSTOMER + DELIVERY
                ============================================== --}}
                <div class="tob-order-information-grid">

                    {{-- DELIVERY ADDRESS --}}
                    <section class="tob-order-detail-card">

                        <div class="tob-order-detail-card-heading">

                            <div>

                                <span>
                                    DELIVERY
                                </span>

                                <h2>
                                    Shipping Address
                                </h2>

                            </div>

                        </div>


                        <div class="tob-order-address">

                            <strong>
                                {{ $order->name }}
                            </strong>

                            <p>
                                {{ $order->address }}
                            </p>


                            @if($order->landmark)

                                <p>
                                    {{ $order->landmark }}
                                </p>

                            @endif


                            <p>
                                {{ $order->city }},
                                {{ $order->state }}
                                - {{ $order->pincode }}
                            </p>


                            <p>
                                {{ $order->country }}
                            </p>


                            <div class="tob-order-contact">

                                <span>
                                    Phone
                                </span>

                                <strong>
                                    {{ $order->phone }}
                                </strong>

                            </div>

                        </div>

                    </section>


                    {{-- PAYMENT --}}
                    <section class="tob-order-detail-card">

                        <div class="tob-order-detail-card-heading">

                            <div>

                                <span>
                                    PAYMENT
                                </span>

                                <h2>
                                    Payment Details
                                </h2>

                            </div>

                        </div>


                        <div class="tob-order-payment-details">

                            <div>

                                <span>
                                    Payment Method
                                </span>

                                <strong>

                                    @if($order->payment_method === 'cod')

                                        Cash on Delivery

                                    @else

                                        {{ strtoupper(
                                            $order->payment_method
                                        ) }}

                                    @endif

                                </strong>

                            </div>


                            <div>

                                <span>
                                    Payment Status
                                </span>

                                <strong class="tob-payment-status">

                                    {{ ucfirst(
                                        $order->payment_status
                                    ) }}

                                </strong>

                            </div>


                            <div>

                                <span>
                                    Email
                                </span>

                                <strong>
                                    {{ $order->email }}
                                </strong>

                            </div>

                        </div>

                    </section>

                </div>


                {{-- =============================================
                     ORDER SUMMARY
                ============================================== --}}
                <section class="tob-order-detail-card tob-order-summary-card">

                    <div class="tob-order-detail-card-heading">

                        <div>

                            <span>
                                PAYMENT SUMMARY
                            </span>

                            <h2>
                                Order Summary
                            </h2>

                        </div>

                    </div>


                    <div class="tob-order-summary">

                        <div>

                            <span>
                                Subtotal
                            </span>

                            <strong>
                                ₹{{ number_format(
                                    (float) $order->subtotal,
                                    2
                                ) }}
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

                                    ₹{{ number_format(
                                        (float) $order->shipping,
                                        2
                                    ) }}

                                @endif

                            </strong>

                        </div>


                        <div class="tob-order-summary-total">

                            <span>
                                Total
                            </span>

                            <strong>
                                ₹{{ number_format(
                                    (float) $order->total,
                                    2
                                ) }}
                            </strong>

                        </div>

                    </div>

                </section>


                {{-- =============================================
                     BOTTOM ACTION
                ============================================== --}}
                <div class="tob-order-detail-bottom">

                    <a
                        href="{{ route('account.orders') }}"
                        class="tob-order-back-button"
                    >
                        ← BACK TO MY ORDERS
                    </a>


                    <a
                        href="{{ route('shop') }}"
                        class="tob-order-shop-button"
                    >
                        CONTINUE SHOPPING
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection