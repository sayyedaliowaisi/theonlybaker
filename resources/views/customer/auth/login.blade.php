@extends('layouts.app')

@section('title', 'Login | The Only Baker')

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

            Login

        </div>


        {{-- =====================================================
             LOGIN SECTION
        ====================================================== --}}
        <div class="tob-auth-section">

            <div class="tob-auth-header">

                <p class="tob-auth-eyebrow">
                    Welcome Back
                </p>

                <h1>
                    Login
                </h1>

                <p>
                    Sign in to your account to manage your
                    details and view your orders.
                </p>

            </div>


            <div class="tob-auth-card">

                {{-- =================================================
                     SUCCESS MESSAGE
                ================================================== --}}
                @if (session('status'))

                    <div class="tob-account-success">
                        {{ session('status') }}
                    </div>

                @endif


                {{-- =================================================
                     VALIDATION ERRORS
                ================================================== --}}
                @if ($errors->any())

                    <div class="tob-auth-errors">

                        @foreach ($errors->all() as $error)

                            <div>
                                {{ $error }}
                            </div>

                        @endforeach

                    </div>

                @endif


                {{-- =================================================
                     LOGIN FORM
                ================================================== --}}
                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                >

                    @csrf


                    {{-- EMAIL --}}
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
                            autofocus
                        >

                    </div>


                    {{-- PASSWORD --}}
                    <div class="tob-auth-field">

                        <label for="password">
                            Password
                        </label>


                        <div class="tob-password-wrap">

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >


                            <button
                                type="button"
                                class="tob-password-toggle"
                                data-password-target="password"
                                aria-label="Show or hide password"
                            >
                                SHOW
                            </button>

                        </div>

                    </div>


                    {{-- =================================================
                         REMEMBER + FORGOT PASSWORD
                    ================================================== --}}
                    <div class="tob-auth-options">

                        <label class="tob-remember">

                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                {{ old('remember') ? 'checked' : '' }}
                            >

                            <span>
                                Remember me
                            </span>

                        </label>


                        <a
                            href="{{ route('password.request') }}"
                            class="tob-forgot-password"
                        >
                            Forgot Password?
                        </a>

                    </div>


                    {{-- LOGIN BUTTON --}}
                    <button
                        type="submit"
                        class="tob-auth-submit"
                    >
                        Login
                    </button>

                </form>


                {{-- =================================================
                     REGISTER
                ================================================== --}}
                <p class="tob-auth-switch">

                    Don't have an account?

                    <a href="{{ route('register') }}">
                        Create Account
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