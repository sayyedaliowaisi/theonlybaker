@extends('layouts.app')

@section('title', 'My Account | The Only Baker')

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

            My Account

        </div>


        {{-- =====================================================
             DASHBOARD SECTION
        ====================================================== --}}
        <div class="tob-dashboard-section">

            {{-- PAGE HEADING --}}
            <div class="tob-dashboard-heading">

                <p>
                    The Only Baker
                </p>

                <h1>
                    My Account
                </h1>

            </div>


            <div class="tob-dashboard-grid">

                {{-- =================================================
                     ACCOUNT SIDEBAR
                ================================================== --}}
                <aside class="tob-account-sidebar">

                    {{-- CUSTOMER INFO --}}
                    <div class="tob-account-user">

                        <strong>
                            {{ $user->name }}
                        </strong>

                        <span>
                            {{ $user->email }}
                        </span>

                    </div>


                    {{-- ACCOUNT NAVIGATION --}}
                    <nav class="tob-account-nav">

                        <a
                            href="{{ route('account.dashboard') }}"
                            class="active"
                        >
                            Dashboard
                        </a>


                        <a href="{{ route('account.orders') }}">
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
                     DASHBOARD CONTENT
                ================================================== --}}
                <div class="tob-dashboard-content">

                    {{-- VERIFICATION SUCCESS --}}
                    @if(session('verification_success'))

                        <div class="tob-account-success">
                            {{ session('verification_success') }}
                        </div>

                    @endif


                    {{-- EMAIL NOT VERIFIED WARNING --}}
                    @if(! $user->hasVerifiedEmail())

                        <div class="tob-verification-warning">

                            Your email address is not verified yet.

                            <a href="{{ route('verification.notice') }}">
                                Verify your email
                            </a>

                            to secure your account.

                        </div>

                    @endif


                    <h2>
                        Hello, {{ $user->name }}
                    </h2>

                    <p>
                        From your account dashboard you can
                        view your orders and manage your
                        account information.
                    </p>


                    {{-- =================================================
                         ACCOUNT STATS
                    ================================================== --}}
                    <div class="tob-dashboard-cards">

                        {{-- TOTAL ORDERS --}}
                        <div class="tob-dashboard-card">

                            <span>
                                Total Orders
                            </span>

                            <strong>
                                {{ $totalOrders }}
                            </strong>

                        </div>


                        {{-- ACTIVE ORDERS --}}
                        <div class="tob-dashboard-card">

                            <span>
                                Active Orders
                            </span>

                            <strong>
                                {{ $pendingOrders }}
                            </strong>

                        </div>


                        {{-- EMAIL --}}
                        <div class="tob-dashboard-card">

                            <span>
                                Email
                            </span>

                            <strong>
                                {{ $user->email }}
                            </strong>


                            @if($user->hasVerifiedEmail())

                                <small class="tob-email-status tob-email-verified">
                                    Verified
                                </small>

                            @else

                                <small class="tob-email-status tob-email-unverified">
                                    Not Verified
                                </small>

                            @endif

                        </div>


                        {{-- PHONE --}}
                        <div class="tob-dashboard-card">

                            <span>
                                Phone
                            </span>

                            <strong>
                                {{ $user->phone ?: 'Not added' }}
                            </strong>

                        </div>

                    </div>


                    {{-- =================================================
                         ACTION BUTTONS
                    ================================================== --}}
                    <div class="tob-account-dashboard-action">

                        <a
                            href="{{ route('account.orders') }}"
                            class="tob-account-primary-link"
                        >
                            View My Orders
                        </a>


                        <a
                            href="{{ route('account.profile') }}"
                            class="tob-account-secondary-link"
                        >
                            Account Details
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection