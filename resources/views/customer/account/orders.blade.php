@extends('layouts.app')

@section('title', 'My Orders | The Only Baker')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
@endpush


@section('content')

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

            My Orders

        </div>


        {{-- =====================================================
             ORDERS SECTION
        ====================================================== --}}
        <div class="tob-dashboard-section">

            <div class="tob-dashboard-heading">

                <p>
                    Your Purchases
                </p>

                <h1>
                    My Orders
                </h1>

            </div>


            <div class="tob-dashboard-grid">

                {{-- =================================================
                     ACCOUNT SIDEBAR
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
                     ORDERS CONTENT
                ================================================== --}}
                <div class="tob-dashboard-content">

                    @if($orders->count())

                        <div class="tob-orders-list">

                            @foreach($orders as $order)

                                <article class="tob-order-card">

                                    {{-- ORDER TOP --}}
                                    <div class="tob-order-card-top">

                                        <div>

                                            <span class="tob-order-label">
                                                Order
                                            </span>

                                            <strong>
                                                #{{ $order->order_number }}
                                            </strong>

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


                                    {{-- ORDER INFORMATION --}}
                                    <div class="tob-order-meta">

                                        <div>

                                            <span>
                                                Date
                                            </span>

                                            <strong>
                                                {{ $order->created_at->format('d M Y') }}
                                            </strong>

                                        </div>


                                        <div>

                                            <span>
                                                Payment
                                            </span>

                                            <strong>

                                                @if($order->payment_method === 'cod')

                                                    Cash on Delivery

                                                @else

                                                    {{ strtoupper($order->payment_method) }}

                                                @endif

                                            </strong>

                                        </div>


                                        <div>

                                            <span>
                                                Amount
                                            </span>

                                            <strong>
                                                ₹{{ number_format(
                                                    (float) $order->total,
                                                    2
                                                ) }}
                                            </strong>

                                        </div>

                                    </div>


                                    {{-- SHIPPING NOTE --}}
                                    @if(is_null($order->shipping))

                                        <p class="tob-order-shipping-note">

                                            Shipping charges, if applicable,
                                            are calculated separately.

                                        </p>

                                    @endif


                                    {{-- VIEW ORDER --}}
                                    <div class="tob-order-card-bottom">

                                        <a
                                            href="{{ route(
                                                'account.orders.show',
                                                $order->order_number
                                            ) }}"
                                            class="tob-order-view"
                                        >
                                            View Order
                                        </a>

                                    </div>

                                </article>

                            @endforeach

                        </div>


                        {{-- PAGINATION --}}
                        @if($orders->hasPages())

                            <div class="tob-account-pagination">
                                {{ $orders->links() }}
                            </div>

                        @endif


                    @else

                        {{-- =================================================
                             EMPTY ORDERS
                        ================================================== --}}
                        <div class="tob-account-empty">

                            <div class="tob-account-empty-icon">
                                ♡
                            </div>

                            <h2>
                                No Orders Yet
                            </h2>

                            <p>
                                You haven't placed any orders
                                from this account yet.
                            </p>

                            <a
                                href="{{ route('shop') }}"
                                class="tob-account-primary-link"
                            >
                                Start Shopping
                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</section>

@endsection