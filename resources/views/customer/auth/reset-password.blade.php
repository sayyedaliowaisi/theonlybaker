@extends('layouts.app')

@section('title', 'Reset Password | The Only Baker')

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

            Reset Password

        </div>


        <div class="tob-auth-section">

            <div class="tob-auth-header">

                <p class="tob-auth-eyebrow">
                    Create New Password
                </p>

                <h1>
                    Reset Password
                </h1>

                <p>
                    Create a new secure password for
                    your The Only Baker account.
                </p>

            </div>


            <div class="tob-auth-card">

                @if($errors->any())

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
                    action="{{ route('password.update') }}"
                >

                    @csrf


                    <input
                        type="hidden"
                        name="token"
                        value="{{ $token }}"
                    >


                    <div class="tob-auth-field">

                        <label for="email">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $email) }}"
                            autocomplete="email"
                            required
                            autofocus
                        >

                    </div>


                    <div class="tob-auth-field">

                        <label for="password">
                            New Password
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
                            Confirm New Password
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
                        Reset Password
                    </button>

                </form>


                <p class="tob-auth-switch">

                    Back to

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