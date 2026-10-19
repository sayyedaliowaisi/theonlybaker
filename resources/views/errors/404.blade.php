
@extends('layouts.app')

@section('title', 'Page Not Found | The Only Baker')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/error-404.css') }}">
@endpush

@section('content')

<main class="tob-error-page">

    <section class="tob-error-section">

        <div class="tob-error-container">

            <div class="tob-error-visual">

                <span class="tob-error-sparkle sparkle-one"
                      aria-hidden="true">✦</span>

                <span class="tob-error-sparkle sparkle-two"
                      aria-hidden="true">✧</span>

                <div class="tob-error-number"
                     aria-label="Error 404">

                    <span>4</span>

                    <div class="tob-cookie-zero"
                         aria-hidden="true">

                        <span class="tob-chip chip-one"></span>
                        <span class="tob-chip chip-two"></span>
                        <span class="tob-chip chip-three"></span>
                        <span class="tob-chip chip-four"></span>
                        <span class="tob-chip chip-five"></span>
                        <span class="tob-chip chip-six"></span>

                    </div>

                    <span>4</span>

                </div>

                <span class="tob-error-sparkle sparkle-three"
                      aria-hidden="true">✦</span>

                <span class="tob-error-sparkle sparkle-four"
                      aria-hidden="true">✧</span>

            </div>

            <span class="tob-error-eyebrow">
                OOPS! SOMETHING'S MISSING
            </span>

            <h1>
                Looks Like This Page
                <em>Crumbled Away.</em>
            </h1>

            <p class="tob-error-description">
                We couldn't find the page you're looking for.
                Perhaps it took a little detour to the cookie jar!
                Let's get you back to something delicious.
            </p>

            <div class="tob-error-actions">

                <a href="{{ url('/') }}"
                   class="tob-error-btn tob-error-btn-primary">

                    Back to Home
                    <span aria-hidden="true">→</span>

                </a>

                <a href="{{ route('shop') }}"
                   class="tob-error-btn tob-error-btn-outline">

                    Explore Our Cookies
                    <span aria-hidden="true">↗</span>

                </a>

            </div>

            <div class="tob-error-divider"></div>

            <div class="tob-error-help">

                <span aria-hidden="true">♡</span>

                <p>
                    Need a little help?
                    <a href="{{ route('contact') }}">
                        Contact our team
                    </a>
                </p>

            </div>

        </div>

    </section>

</main>

@endsection
