@extends('layouts.app')

@section('title', 'Account Details | The Only Baker')

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

            Account Details

        </div>


        <div class="tob-dashboard-section">

            {{-- =====================================================
                 HEADING
            ====================================================== --}}
            <div class="tob-dashboard-heading">

                <p>
                    Your Profile
                </p>

                <h1>
                    Account Details
                </h1>

            </div>


            <div class="tob-dashboard-grid">

                {{-- =================================================
                     SIDEBAR
                ================================================== --}}
                <aside class="tob-account-sidebar">

                    <div class="tob-account-user">

                        <strong>
                            {{ $user->name }}
                        </strong>

                        <span>
                            {{ $user->email }}
                        </span>

                    </div>


                    <nav class="tob-account-nav">

                        <a href="{{ route('account.dashboard') }}">
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


                        <a
                            href="{{ route('account.profile') }}"
                            class="active"
                        >
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
                     CONTENT
                ================================================== --}}
                <div class="tob-account-profile-content">

                    {{-- =================================================
                         PROFILE DETAILS
                    ================================================== --}}
                    <div class="tob-profile-card">

                        <div class="tob-profile-card-heading">

                            <div>

                                <span>
                                    Personal Information
                                </span>

                                <h2>
                                    Account Details
                                </h2>

                            </div>

                            <p>
                                Update your personal information
                                associated with your account.
                            </p>

                        </div>


                        {{-- PROFILE SUCCESS --}}
                        @if(session('profile_success'))

                            <div class="tob-account-success">
                                {{ session('profile_success') }}
                            </div>

                        @endif


                        {{-- PROFILE ERRORS --}}
                        @if(
                            $errors->any() &&
                            !$errors->passwordUpdate->any()
                        )

                            <div class="tob-auth-errors">

                                @foreach($errors->all() as $error)

                                    <div>
                                        {{ $error }}
                                    </div>

                                @endforeach

                            </div>

                        @endif


                        <form
                            method="POST"
                            action="{{ route('account.profile.update') }}"
                            class="tob-profile-form"
                        >

                            @csrf
                            @method('PUT')


                            <div class="tob-profile-fields">

                                {{-- FULL NAME --}}
                                <div class="tob-auth-field">

                                    <label for="profile_name">
                                        Full Name
                                    </label>

                                    <input
                                        type="text"
                                        id="profile_name"
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        autocomplete="name"
                                        required
                                    >

                                </div>


                                {{-- EMAIL --}}
                                <div class="tob-auth-field">

                                    <label for="profile_email">
                                        Email Address
                                    </label>

                                    <input
                                        type="email"
                                        id="profile_email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        autocomplete="email"
                                        required
                                    >


                                    {{-- EMAIL VERIFICATION STATUS --}}
                                    @if($user->hasVerifiedEmail())

                                        <small class="tob-email-status tob-email-verified">
                                            Verified
                                        </small>

                                    @else

                                        <small class="tob-email-status tob-email-unverified">
                                            Not Verified
                                        </small>


                                        <div class="tob-profile-verification-link">

                                            <a href="{{ route('verification.notice') }}">
                                                Verify this email address
                                            </a>

                                        </div>

                                    @endif

                                </div>


                                {{-- PHONE --}}
                                <div class="tob-auth-field tob-profile-full">

                                    <label for="profile_phone">
                                        Phone Number
                                    </label>

                                    <input
                                        type="tel"
                                        id="profile_phone"
                                        name="phone"
                                        value="{{ old('phone', $user->phone) }}"
                                        placeholder="Enter your phone number"
                                        autocomplete="tel"
                                    >

                                </div>

                            </div>


                            <button
                                type="submit"
                                class="tob-profile-submit"
                            >
                                Save Changes
                            </button>

                        </form>

                    </div>


                    {{-- =================================================
                         PASSWORD
                    ================================================== --}}
                    <div class="tob-profile-card">

                        <div class="tob-profile-card-heading">

                            <div>

                                <span>
                                    Security
                                </span>

                                <h2>
                                    Change Password
                                </h2>

                            </div>

                            <p>
                                Use your current password to
                                create a new secure password.
                            </p>

                        </div>


                        {{-- PASSWORD SUCCESS --}}
                        @if(session('password_success'))

                            <div class="tob-account-success">
                                {{ session('password_success') }}
                            </div>

                        @endif


                        {{-- PASSWORD ERRORS --}}
                        @if($errors->passwordUpdate->any())

                            <div class="tob-auth-errors">

                                @foreach(
                                    $errors->passwordUpdate->all()
                                    as $error
                                )

                                    <div>
                                        {{ $error }}
                                    </div>

                                @endforeach

                            </div>

                        @endif


                        <form
                            method="POST"
                            action="{{ route('account.password.update') }}"
                            class="tob-profile-form"
                        >

                            @csrf
                            @method('PUT')


                            {{-- CURRENT PASSWORD --}}
                            <div class="tob-auth-field">

                                <label for="current_password">
                                    Current Password
                                </label>


                                <div class="tob-password-wrap">

                                    <input
                                        type="password"
                                        id="current_password"
                                        name="current_password"
                                        autocomplete="current-password"
                                        required
                                    >


                                    <button
                                        type="button"
                                        class="tob-password-toggle"
                                        data-password-target="current_password"
                                    >
                                        SHOW
                                    </button>

                                </div>

                            </div>


                            <div class="tob-profile-fields">

                                {{-- NEW PASSWORD --}}
                                <div class="tob-auth-field">

                                    <label for="new_password">
                                        New Password
                                    </label>


                                    <div class="tob-password-wrap">

                                        <input
                                            type="password"
                                            id="new_password"
                                            name="password"
                                            autocomplete="new-password"
                                            required
                                        >


                                        <button
                                            type="button"
                                            class="tob-password-toggle"
                                            data-password-target="new_password"
                                        >
                                            SHOW
                                        </button>

                                    </div>

                                </div>


                                {{-- CONFIRM PASSWORD --}}
                                <div class="tob-auth-field">

                                    <label for="password_confirmation">
                                        Confirm New Password
                                    </label>


                                    <div class="tob-password-wrap">

                                        <input
                                            type="password"
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            autocomplete="new-password"
                                            required
                                        >


                                        <button
                                            type="button"
                                            class="tob-password-toggle"
                                            data-password-target="password_confirmation"
                                        >
                                            SHOW
                                        </button>

                                    </div>

                                </div>

                            </div>


                            <button
                                type="submit"
                                class="tob-profile-submit"
                            >
                                Change Password
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection


@push('scripts')
    <script src="{{ asset('js/account.js') }}"></script>
@endpush