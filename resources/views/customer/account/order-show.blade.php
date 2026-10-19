
@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' | The Only Baker')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-tracking.css') }}?v=1">
@endpush

@section('content')

@php
    $products = config('products', []);

    $status = strtolower($order->order_status ?? 'pending');

    $statusLabels = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];

    $statusLabel = $statusLabels[$status] ?? ucfirst($status);

    $itemCount = $order->items->sum('quantity');
@endphp

<section class="tob-account-page">

    <div class="tob-account-container">

        {{-- BREADCRUMB --}}
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

            <span>
                Order #{{ $order->order_number }}
            </span>

        </div>

        {{-- PAGE HEADING --}}
        <div class="tob-order-detail-heading">

            <div>

                <p>ORDER DETAILS</p>

                <h1>
                    Order #{{ $order->order_number }}
                </h1>

                <span>
                    Placed on
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </span>

            </div>

            <span
                class="tob-order-status tob-status-{{ $status }}"
            >
                {{ $statusLabel }}
            </span>

        </div>

        <div class="tob-dashboard-grid">

            {{-- ACCOUNT SIDEBAR --}}
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

                    <a href="{{ route('account.addresses.index') }}">
                        My Addresses
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

            {{-- MAIN CONTENT --}}
            <div class="tob-dashboard-content">

                {{-- ORDER TRACKING --}}
                @include('customer.account.partials.order-tracker')

                {{-- ORDER ITEMS --}}
                <section class="tob-order-detail-card">

                    <div class="tob-order-detail-card-heading">

                        <div>

                            <span>YOUR ORDER</span>

                            <h2>Order Items</h2>

                        </div>

                        <strong class="tob-order-item-count">
                            {{ $itemCount }}
                            {{ \Illuminate\Support\Str::plural('Item', $itemCount) }}
                        </strong>

                    </div>

                    <div class="tob-order-detail-items">

                        @forelse($order->items as $item)

                            @php
                                $productExists =
                                    !empty($item->product_slug)
                                    && isset($products[$item->product_slug]);

                                $productUrl = $productExists
                                    ? route('product.show', $item->product_slug)
                                    : null;

                                $productImage = $productExists
                                    ? ($products[$item->product_slug]['image'] ?? null)
                                    : $item->image;
                            @endphp

                            <article class="tob-order-detail-item">

                                <div class="tob-order-detail-product-image">

                                    @if($productImage)

                                        @if($productUrl)
                                            <a href="{{ $productUrl }}">
                                        @endif

                                        <img
                                            src="{{ asset($productImage) }}"
                                            alt="{{ $item->product_name }}"
                                            loading="lazy"
                                        >

                                        @if($productUrl)
                                            </a>
                                        @endif

                                    @else

                                        <div class="tob-order-image-placeholder">
                                            TOB
                                        </div>

                                    @endif

                                </div>

                                <div class="tob-order-detail-product-info">

                                    <span>THE ONLY BAKER</span>

                                    <h3>

                                        @if($productUrl)

                                            <a href="{{ $productUrl }}">
                                                {{ $item->product_name }}
                                            </a>

                                        @else

                                            {{ $item->product_name }}

                                        @endif

                                    </h3>

                                    <div class="tob-order-product-meta">

                                        <span>
                                            ₹{{ number_format((float) $item->price, 2) }}
                                        </span>

                                        <span>×</span>

                                        <span>
                                            Qty {{ $item->quantity }}
                                        </span>

                                    </div>

                                    @if($status === 'delivered' && $productUrl)

                                        <a
                                            href="{{ $productUrl }}#reviews"
                                            class="tob-order-review-product"
                                        >
                                            REVIEW PRODUCT
                                        </a>

                                    @endif

                                </div>

                                <div class="tob-order-detail-item-total">

                                    <span>Total</span>

                                    <strong>
                                        ₹{{ number_format((float) $item->total, 2) }}
                                    </strong>

                                </div>

                            </article>

                        @empty

                            <p>
                                No items found for this order.
                            </p>

                        @endforelse

                    </div>

                </section>

                {{-- DELIVERY + PAYMENT --}}
                <div class="tob-order-information-grid">

                    {{-- SHIPPING ADDRESS --}}
                    <section class="tob-order-detail-card">

                        <div class="tob-order-detail-card-heading">

                            <div>
                                <span>DELIVERY</span>
                                <h2>Shipping Address</h2>
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
                                {{ $order->country ?: 'India' }}
                            </p>

                            <div class="tob-order-contact">

                                <span>Phone</span>

                                <strong>
                                    {{ $order->phone }}
                                </strong>

                            </div>

                        </div>

                    </section>

                    {{-- PAYMENT DETAILS --}}
                    <section class="tob-order-detail-card">

                        <div class="tob-order-detail-card-heading">

                            <div>
                                <span>PAYMENT</span>
                                <h2>Payment Details</h2>
                            </div>

                        </div>

                        <div class="tob-order-payment-details">

                            <div>

                                <span>Payment Method</span>

                                <strong>
                                    {{ $order->payment_method === 'cod'
                                        ? 'Cash on Delivery'
                                        : strtoupper($order->payment_method ?? 'N/A') }}
                                </strong>

                            </div>

                            <div>

                                <span>Payment Status</span>

                                <strong class="tob-payment-status">
                                    {{ ucfirst($order->payment_status ?? 'pending') }}
                                </strong>

                            </div>

                            <div>

                                <span>Email</span>

                                <strong>
                                    {{ $order->email }}
                                </strong>

                            </div>

                        </div>

                    </section>

                </div>

                {{-- ORDER SUMMARY --}}
                <section class="tob-order-detail-card tob-order-summary-card">

                    <div class="tob-order-detail-card-heading">

                        <div>
                            <span>PAYMENT SUMMARY</span>
                            <h2>Order Summary</h2>
                        </div>

                    </div>

                    <div class="tob-order-summary">

                        <div>

                            <span>Subtotal</span>

                            <strong>
                                ₹{{ number_format((float) $order->subtotal, 2) }}
                            </strong>

                        </div>

                        <div>

                            <span>Shipping</span>

                            <strong>

                                @if(is_null($order->shipping))

                                    Calculated separately

                                @elseif((float) $order->shipping === 0.0)

                                    FREE

                                @else

                                    ₹{{ number_format((float) $order->shipping, 2) }}

                                @endif

                            </strong>

                        </div>

                        <div class="tob-order-summary-total">

                            <span>Total</span>

                            <strong>
                                ₹{{ number_format((float) $order->total, 2) }}
                            </strong>

                        </div>

                    </div>

                </section>

                {{-- BOTTOM ACTIONS --}}
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
