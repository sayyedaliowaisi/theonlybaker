@extends('layouts.app')

@section('title', 'Create Account | The Only Baker')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
@endpush


@section('content')

<section class="tob-account-page">

    <div class="tob-account-container">

        <div class="tob-account-breadcrumb">

            <a href="{{ route('home') }}">
                Home
            </a>

            <span>›</span>

            Create Account

        </div>


        <div class="tob-auth-section">

            <div class="tob-auth-header">

                <p class="tob-auth-eyebrow">
                    Join The Only Baker
                </p>

                <h1>
                    Create Account
                </h1>

                <p>
                    Create your account for a smoother
                    shopping experience.
                </p>

            </div>


            <div class="tob-auth-card">

                @if ($errors->any())

                    <div class="tob-auth-errors">

                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route('register.store') }}"
                >

                    @csrf


                    <div class="tob-auth-field">

                        <label for="name">
                            Full Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter your full name"
                            autocomplete="name"
                            required
                            autofocus
                        >

                    </div>


                    <div class="tob-auth-field">

                        <label for="email">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            autocomplete="email"
                            required
                        >

                    </div>


                    <div class="tob-auth-field">

                        <label for="phone">
                            Phone Number
                        </label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="Enter your phone number"
                            autocomplete="tel"
                        >

                    </div>


                    <div class="tob-auth-field">

                        <label for="password">
                            Password
                        </label>


                        <div class="tob-password-wrap">

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Minimum 8 characters"
                                autocomplete="new-password"
                                required
                            >


                            <button
                                type="button"
                                class="tob-password-toggle"
                                data-password-target="password"
                            >
                                SHOW
                            </button>

                        </div>

                    </div>


                    <div class="tob-auth-field">

                        <label for="password_confirmation">
                            Confirm Password
                        </label>


                        <div class="tob-password-wrap">

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Enter password again"
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


                    <button
                        type="submit"
                        class="tob-auth-submit"
                    >
                        Create Account
                    </button>

                </form>


                <p class="tob-auth-switch">

                    Already have an account?

                    <a href="{{ route('login') }}">
                        Login
                    </a>

                </p>

            </div>

        </div>

    </div>

</section>

@endsection


@push('scripts')

<script src="{{ asset('js/account.js') }}"></script>

@endpush