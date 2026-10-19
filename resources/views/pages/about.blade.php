
@extends('layouts.app')

@section('title', 'About Us | The Only Baker')

@section(
    'meta_description',
    'Discover The Only Baker, our passion for cookies, thoughtful baking and the care behind every delicious creation.'
)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')

<div class="tob-about-page">

    {{-- ABOUT US HERO --}}
    <section class="tob-about-hero">

        <div class="tob-about-container">

            <span class="tob-about-eyebrow">
                THE ONLY BAKER
            </span>

            <h1>
                Baked With Love,
                <em>Shared With Joy.</em>
            </h1>

            <p>
                Discover the passion, creativity and care
                behind every delicious creation.
            </p>

            <a href="#our-story" class="tob-about-btn">
                Discover Our Story
                <span aria-hidden="true">↗</span>
            </a>

        </div>

    </section>

    
{{-- =====================================================
     OUR STORY SECTION
====================================================== --}}

<section
    class="tob-about-story"
    id="our-story"
    aria-labelledby="tob-story-title"
>
    <div class="tob-about-container">

        <div class="tob-about-story-grid">

            {{-- LEFT: IMAGE --}}
            <div class="tob-about-story-visual">

                <div class="tob-about-story-image-wrap">

                    <img
                        src="{{ asset('images/about/our-story.jpg') }}"
                        alt="Artisan cookies prepared with care"
                        loading="lazy"
                        decoding="async"
                        class="tob-about-story-image"
                    >

                </div>

                <div class="tob-about-story-badge">
                    <span class="tob-about-story-badge-icon"
                          aria-hidden="true">✦</span>

                    <span>
                        MADE WITH CARE
                    </span>
                </div>

            </div>

            {{-- RIGHT: CONTENT --}}
            <div class="tob-about-story-content">

                <span class="tob-about-eyebrow">
                    A LITTLE ABOUT US
                </span>

                <h2 id="tob-story-title">
                    A Passion for Baking.
                    <em>A Love for Sharing.</em>
                </h2>

                <div class="tob-about-story-divider"></div>

                <p class="tob-about-story-lead">
                    At The Only Baker, we believe that
                    the simplest moments can become
                    the sweetest memories.
                </p>

                <p>
                    Our love for baking inspires us to
                    create cookies that bring warmth,
                    comfort and a little happiness
                    to everyday moments.
                </p>

                <p>
                    Whether you're treating yourself,
                    sharing with family or choosing
                    a thoughtful gift, we want every
                    bite to feel special.
                </p>

                <div class="tob-about-story-signature">
                    <span class="tob-about-story-signature-line"></span>

                    <div>
                        <strong>Made for Sweet Moments</strong>
                        <small>The Only Baker</small>
                    </div>
                </div>

                <a
                    href="{{ route('shop') }}"
                    class="tob-about-btn tob-about-story-btn"
                >
                    Explore Our Cookies
                    <span aria-hidden="true">↗</span>
                </a>

            </div>

        </div>

    </div>
</section>


{{-- =====================================================
     THE ART OF BAKING
====================================================== --}}

<section
    class="tob-about-craft"
    id="our-craft"
    aria-labelledby="tob-craft-title"
>
    <div class="tob-about-container">

        {{-- SECTION HEADING --}}
        <div class="tob-about-craft-heading">

            <span class="tob-about-eyebrow">
                BEHIND EVERY BITE
            </span>

            <h2 id="tob-craft-title">
                The Art of
                <em>Thoughtful Baking.</em>
            </h2>

            <p>
                We believe baking is about more than
                following a recipe. It's about paying
                attention to the little details that
                make every moment more enjoyable.
            </p>

        </div>

        {{-- EDITORIAL IMAGE --}}
        <div class="tob-about-craft-visual">

            <img
                src="{{ asset('images/about/baking-process.jpg') }}"
                alt="Cookie dough being prepared in a bakery"
                loading="lazy"
                decoding="async"
                class="tob-about-craft-image"
            >

            <div class="tob-about-craft-image-label">
                <span aria-hidden="true">✦</span>
                THE BEAUTY IS IN THE DETAILS
            </div>

        </div>

        {{-- FEATURE CARDS --}}
        <div class="tob-about-craft-grid">

            <article class="tob-about-craft-card">

                <span
                    class="tob-about-craft-number"
                    aria-hidden="true"
                >
                    01
                </span>

                <div class="tob-about-craft-icon"
                     aria-hidden="true">
                    ✧
                </div>

                <h3>Thoughtful Ingredients</h3>

                <p>
                    We appreciate how carefully chosen
                    ingredients can bring wonderful
                    flavour and character to a recipe.
                </p>

            </article>

            <article class="tob-about-craft-card">

                <span
                    class="tob-about-craft-number"
                    aria-hidden="true"
                >
                    02
                </span>

                <div class="tob-about-craft-icon"
                     aria-hidden="true">
                    ♡
                </div>

                <h3>Attention to Detail</h3>

                <p>
                    From texture to presentation,
                    the little details are what make
                    a sweet treat feel memorable.
                </p>

            </article>

            <article class="tob-about-craft-card">

                <span
                    class="tob-about-craft-number"
                    aria-hidden="true"
                >
                    03
                </span>

                <div class="tob-about-craft-icon"
                     aria-hidden="true">
                    ✦
                </div>

                <h3>Moments Worth Sharing</h3>

                <p>
                    We love the idea of bringing
                    people together through simple,
                    delicious moments of happiness.
                </p>

            </article>

        </div>

    </div>
</section>


{{-- =====================================================
     OUR VALUES SECTION
====================================================== --}}

<section
    class="tob-about-values"
    id="our-values"
    aria-labelledby="tob-values-title"
>
    <div class="tob-about-container">

        <div class="tob-about-values-heading">

            <span class="tob-about-eyebrow">
                WHAT MATTERS TO US
            </span>

            <h2 id="tob-values-title">
                The Little Things
                <em>That Matter Most.</em>
            </h2>

            <p>
                Our values reflect the experience
                we want to create — thoughtful,
                welcoming and full of sweet moments.
            </p>

        </div>

        <div class="tob-about-values-grid">

            {{-- QUALITY --}}
            <article class="tob-about-value-card">

                <div class="tob-about-value-icon"
                     aria-hidden="true">
                    ✦
                </div>

                <span class="tob-about-value-number">01</span>

                <h3>Quality</h3>

                <p>
                    We value flavour, texture and
                    attention to detail in every
                    baking experience.
                </p>

            </article>

            {{-- FRESHNESS --}}
            <article class="tob-about-value-card">

                <div class="tob-about-value-icon"
                     aria-hidden="true">
                    ❋
                </div>

                <span class="tob-about-value-number">02</span>

                <h3>Freshness</h3>

                <p>
                    We believe that freshness adds
                    something special to the
                    enjoyment of a delicious treat.
                </p>

            </article>

            {{-- CARE --}}
            <article class="tob-about-value-card">

                <div class="tob-about-value-icon"
                     aria-hidden="true">
                    ♡
                </div>

                <span class="tob-about-value-number">03</span>

                <h3>Care</h3>

                <p>
                    We appreciate the small details
                    that make gifting, sharing and
                    everyday indulgence meaningful.
                </p>

            </article>

            {{-- CUSTOMER HAPPINESS --}}
            <article class="tob-about-value-card">

                <div class="tob-about-value-icon"
                     aria-hidden="true">
                    ☺
                </div>

                <span class="tob-about-value-number">04</span>

                <h3>Customer Happiness</h3>

                <p>
                    We want every interaction
                    with The Only Baker to feel
                    warm, simple and enjoyable.
                </p>

            </article>

        </div>

        <div class="tob-about-values-footer">
            <span class="tob-about-values-line"></span>

            <p>
                Thoughtful baking. Sweet connections.
                Memorable moments.
            </p>

            <span class="tob-about-values-line"></span>
        </div>

    </div>
</section>



{{-- =====================================================
     DISCOVER OUR COOKIES
====================================================== --}}

<section
    class="tob-about-discover"
    id="discover-cookies"
    aria-labelledby="tob-discover-title"
>
    <div class="tob-about-container">

        {{-- SECTION HEADING --}}
        <div class="tob-about-discover-heading">

            <span class="tob-about-eyebrow">
                SOMETHING SWEET AWAITS
            </span>

            <h2 id="tob-discover-title">
                Discover Our
                <em>Cookie Collections.</em>
            </h2>

            <p>
                From chocolatey indulgence to
                nutty favourites and timeless
                classics, find a little something
                for every sweet moment.
            </p>

        </div>

        {{-- CATEGORY GRID --}}
        <div class="tob-about-discover-grid">

            {{-- CHOCOLATE COOKIES --}}
            <article class="tob-about-discover-card">

                <a
                    href="{{ route('shop', ['category' => 'chocolate']) }}"
                    class="tob-about-discover-link"
                    aria-label="Shop chocolate cookies"
                >

                    <div class="tob-about-discover-image-wrap">

                        <img
                            src="{{ asset('images/about/chocolate-cookies.jpg') }}"
                            alt="Chocolate cookies"
                            loading="lazy"
                            decoding="async"
                        >

                        <span class="tob-about-discover-arrow"
                              aria-hidden="true">↗</span>

                    </div>

                    <div class="tob-about-discover-content">

                        <span class="tob-about-discover-number">
                            01 / THE INDULGENT
                        </span>

                        <h3>Chocolate Cookies</h3>

                        <p>
                            Rich, chocolatey treats
                            for moments of indulgence.
                        </p>

                        <span class="tob-about-discover-cta">
                            Explore Collection
                            <span aria-hidden="true">→</span>
                        </span>

                    </div>

                </a>

            </article>

            {{-- DRY FRUIT COOKIES --}}
            <article class="tob-about-discover-card">

                <a
                    href="{{ route('shop', ['category' => 'dry-fruit']) }}"
                    class="tob-about-discover-link"
                    aria-label="Shop dry fruit cookies"
                >

                    <div class="tob-about-discover-image-wrap">

                        <img
                            src="{{ asset('images/about/dry-fruit-cookies.jpg') }}"
                            alt="Dry fruit cookies"
                            loading="lazy"
                            decoding="async"
                        >

                        <span class="tob-about-discover-arrow"
                              aria-hidden="true">↗</span>

                    </div>

                    <div class="tob-about-discover-content">

                        <span class="tob-about-discover-number">
                            02 / THE NUTTY
                        </span>

                        <h3>Dry Fruit Cookies</h3>

                        <p>
                            Nutty flavours and
                            delightful textures
                            in every bite.
                        </p>

                        <span class="tob-about-discover-cta">
                            Explore Collection
                            <span aria-hidden="true">→</span>
                        </span>

                    </div>

                </a>

            </article>

            {{-- CLASSIC COOKIES --}}
            <article class="tob-about-discover-card">

                <a
                    href="{{ route('shop', ['category' => 'classic']) }}"
                    class="tob-about-discover-link"
                    aria-label="Shop classic cookies"
                >

                    <div class="tob-about-discover-image-wrap">

                        <img
                            src="{{ asset('images/about/classic-cookies.jpg') }}"
                            alt="Classic butter cookies"
                            loading="lazy"
                            decoding="async"
                        >

                        <span class="tob-about-discover-arrow"
                              aria-hidden="true">↗</span>

                    </div>

                    <div class="tob-about-discover-content">

                        <span class="tob-about-discover-number">
                            03 / THE TIMELESS
                        </span>

                        <h3>Classic Cookies</h3>

                        <p>
                            Familiar flavours,
                            comforting sweetness
                            and timeless favourites.
                        </p>

                        <span class="tob-about-discover-cta">
                            Explore Collection
                            <span aria-hidden="true">→</span>
                        </span>

                    </div>

                </a>

            </article>

        </div>

        {{-- BOTTOM CTA --}}
        <div class="tob-about-discover-footer">

            <p>
                There's always something
                delicious waiting to be discovered.
            </p>

            <a
                href="{{ route('shop') }}"
                class="tob-about-btn"
            >
                Shop All Cookies
                <span aria-hidden="true">↗</span>
            </a>

        </div>

    </div>
</section>



{{-- =====================================================
     ABOUT US — FINAL CTA SECTION
====================================================== --}}

<section
    class="tob-about-final-cta"
    aria-labelledby="tob-about-final-title"
>
    <div class="tob-about-final-background"
         aria-hidden="true"></div>

    <div class="tob-about-final-overlay"
         aria-hidden="true"></div>

    <div class="tob-about-container">

        <div class="tob-about-final-content">

            <span class="tob-about-final-eyebrow">
                A LITTLE JOY IN EVERY BITE
            </span>

            <div class="tob-about-final-decoration"
                 aria-hidden="true">
                ✦
            </div>

            <h2 id="tob-about-final-title">
                Every Cookie Has a Story.
                <em>Find Yours.</em>
            </h2>

            <p>
                Discover delicious flavours,
                thoughtful treats and sweet
                moments worth sharing.
            </p>

            <div class="tob-about-final-actions">

                <a
                    href="{{ route('shop') }}"
                    class="tob-about-final-btn tob-about-final-btn-primary"
                >
                    Shop Our Cookies
                    <span aria-hidden="true">↗</span>
                </a>

                <a
                    href="{{ route('collections') }}"
                    class="tob-about-final-btn tob-about-final-btn-outline"
                >
                    Explore Collections
                    <span aria-hidden="true">→</span>
                </a>

            </div>

            <span class="tob-about-final-bottom-text">
                THE ONLY BAKER · MADE FOR SWEET MOMENTS
            </span>

        </div>

    </div>
</section>



</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/about.js') }}" defer></script>
@endpush
