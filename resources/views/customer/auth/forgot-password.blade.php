@extends('layouts.app')

@section('title', 'Forgot Password | The Only Baker')

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

            <a href="{{ route('login') }}">
                Login
            </a>

            <span>›</span>

            Forgot Password

        </div>


        <div class="tob-auth-section">

            <div class="tob-auth-header">

                <p class="tob-auth-eyebrow">
                    Account Recovery
                </p>

                <h1>
                    Forgot Password?
                </h1>

                <p>
                    Enter the email address associated
                    with your account and we'll send you
                    a secure password reset link.
                </p>

            </div>


            <div class="tob-auth-card">

                @if(session('status'))

                    <div class="tob-account-success">
                        {{ session('status') }}
                    </div>

                @endif


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
                    action="{{ route('password.email') }}"
                >

                    @csrf


                    <div class="tob-auth-field">

                        <label for="email">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your registered email"
                            autocomplete="email"
                            required
                            autofocus
                        >

                    </div>


                    <button
                        type="submit"
                        class="tob-auth-submit"
                    >
                        Send Reset Link
                    </button>

                </form>


                <p class="tob-auth-switch">

                    Remember your password?

                    <a href="{{ route('login') }}">
                        Back to Login
                    </a>

                </p>

            </div>

        </div>

    </div>

</section>

@endsection