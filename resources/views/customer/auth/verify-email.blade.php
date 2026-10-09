@extends('layouts.app')

@section('title', 'Verify Email | The Only Baker')

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

            Verify Email

        </div>


        <div class="tob-auth-section">

            <div class="tob-auth-header">

                <p class="tob-auth-eyebrow">
                    One More Step
                </p>

                <h1>
                    Verify Your Email
                </h1>

                <p>
                    We've sent a verification link to
                    your registered email address.
                </p>

            </div>


            <div class="tob-auth-card tob-verification-card">

                <div class="tob-verification-icon">
                    ✉
                </div>


                <h2>
                    Check Your Inbox
                </h2>


                <p class="tob-verification-text">

                    We sent a verification link to

                    <strong>
                        {{ auth()->user()->email }}
                    </strong>

                    Click the link in that email to
                    verify your account.

                </p>


                @if(session('verification_sent'))

                    <div class="tob-account-success">

                        {{ session('verification_sent') }}

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route('verification.send') }}"
                >

                    @csrf


                    <button
                        type="submit"
                        class="tob-auth-submit"
                    >
                        Resend Verification Email
                    </button>

                </form>


                <div class="tob-verification-actions">

                    <a href="{{ route('account.dashboard') }}">
                        Go to My Account
                    </a>

                    <a href="{{ route('shop') }}">
                        Continue Shopping
                    </a>

                </div>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="tob-verification-logout"
                >

                    @csrf

                    <button type="submit">
                        Logout
                    </button>

                </form>

            </div>

        </div>

    </div>

</section>

@endsection