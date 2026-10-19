
@extends('layouts.app')

@section('title', 'Gifting | The Only Baker')

@section('meta_description', 'Celebrate special moments with premium cookies and thoughtful gifting from The Only Baker. Explore personal, festive and corporate gifting.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gifting.css') }}">
@endpush

@section('content')

<section class="gift-hero">
    <div class="gift-hero-content">
        <span class="gift-eyebrow">THE ART OF THOUGHTFUL GIFTING</span>

        <h1>
            A Little Gift.
            <em>A Lot of Love.</em>
        </h1>

        <p>
            Some moments deserve something truly special.
            Share happiness with beautifully crafted cookies,
            thoughtfully presented for every celebration.
        </p>

        <div class="gift-hero-actions">
            <a href="#gift-collections" class="gift-btn gift-btn-primary">
                Explore Gifting
            </a>

            <a href="#gift-enquiry" class="gift-btn gift-btn-outline">
                Enquire for Bulk Orders
            </a>
        </div>
    </div>

    <div class="gift-hero-visual">
        <img
            src="{{ asset('images/gifting/gifting-hero.jpg') }}"
            alt="Premium cookie gifting by The Only Baker"
            loading="eager"
        >
    </div>
</section>


<section class="gift-intro gift-section">
    <span class="gift-eyebrow">MADE WITH LOVE, SHARED WITH JOY</span>

    <h2>Because Every Gesture Matters</h2>

    <p>
        Whether it's a birthday, a festive celebration, a
        heartfelt thank-you or a thoughtful corporate gesture,
        our freshly baked cookies make every occasion sweeter.
    </p>
</section>


<section class="gift-collections gift-section" id="gift-collections">
    <div class="gift-section-heading">
        <span class="gift-eyebrow">SOMETHING FOR EVERY OCCASION</span>
        <h2>Discover Our Gifting Experiences</h2>
        <p>Thoughtfully curated for life's sweetest moments.</p>
    </div>

    <div class="gift-card-grid">

        <article class="gift-card">
            <div class="gift-card-image">
                <img
                    src="{{ asset('images/gifting/personal-gifting.jpg') }}"
                    alt="Personal cookie gifting"
                    loading="lazy"
                >
            </div>

            <div class="gift-card-content">
                <span>01 / PERSONAL</span>
                <h3>Personal Gifting</h3>

                <p>
                    Make birthdays, anniversaries and everyday
                    moments feel extra special.
                </p>

                <a href="#gift-enquiry">
                    Enquire Now <span aria-hidden="true">↗</span>
                </a>
            </div>
        </article>


        <article class="gift-card">
            <div class="gift-card-image">
                <img
                    src="{{ asset('images/gifting/corporate-gifting.jpg') }}"
                    alt="Corporate cookie gifting"
                    loading="lazy"
                >
            </div>

            <div class="gift-card-content">
                <span>02 / CORPORATE</span>
                <h3>Corporate Gifting</h3>

                <p>
                    Celebrate partnerships, appreciate teams
                    and delight clients with thoughtful treats.
                </p>

                <a href="#gift-enquiry">
                    Enquire Now <span aria-hidden="true">↗</span>
                </a>
            </div>
        </article>


        <article class="gift-card">
            <div class="gift-card-image">
                <img
                    src="{{ asset('images/gifting/festive-gifting.jpg') }}"
                    alt="Festive cookie gifting"
                    loading="lazy"
                >
            </div>

            <div class="gift-card-content">
                <span>03 / CELEBRATIONS</span>
                <h3>Festive Gifting</h3>

                <p>
                    Add sweetness to festive gatherings and
                    meaningful celebrations.
                </p>

                <a href="#gift-enquiry">
                    Enquire Now <span aria-hidden="true">↗</span>
                </a>
            </div>
        </article>

    </div>
</section>


<section class="gift-feature">
    <div class="gift-feature-image">
        <img
            src="{{ asset('images/gifting/gift-box.jpg') }}"
            alt="Elegant cookie gift box"
            loading="lazy"
        >
    </div>

    <div class="gift-feature-content">
        <span class="gift-eyebrow">THOUGHTFUL FROM THE INSIDE OUT</span>

        <h2>
            More Than Cookies.
            <em>A Beautiful Experience.</em>
        </h2>

        <p>
            Great gifts are remembered long after they're opened.
            From delicious flavours to elegant presentation,
            we believe gifting should feel personal and memorable.
        </p>

        <a href="#gift-enquiry" class="gift-btn gift-btn-primary">
            Plan Your Gift
        </a>
    </div>
</section>


<section class="gift-process gift-section">
    <div class="gift-section-heading">
        <span class="gift-eyebrow">SIMPLE & THOUGHTFUL</span>
        <h2>How Gifting Works</h2>
    </div>

    <div class="gift-process-grid">
        <div>
            <span>01</span>
            <h3>Share Your Occasion</h3>
            <p>Tell us who you're gifting and what you're celebrating.</p>
        </div>

        <div>
            <span>02</span>
            <h3>Discuss Your Requirements</h3>
            <p>Share your preferred quantities, budget and delivery details.</p>
        </div>

        <div>
            <span>03</span>
            <h3>Confirm Your Order</h3>
            <p>Our team will review your request and discuss the next steps.</p>
        </div>
    </div>
</section>


<section class="gift-enquiry gift-section" id="gift-enquiry">
    <div class="gift-enquiry-content">
        <span class="gift-eyebrow">LET'S CREATE SOMETHING SPECIAL</span>

        <h2>Planning a Special Gift?</h2>

        <p>
            Looking for personal, festive or corporate gifting?
            Tell us what you have in mind, and our team
            will help you explore the possibilities.
        </p>

        <div class="gift-enquiry-points">
            <span>✦ Personal Occasions</span>
            <span>✦ Corporate Requirements</span>
            <span>✦ Festive Celebrations</span>
            <span>✦ Bulk Order Enquiries</span>
        </div>
    </div>

    <div class="gift-enquiry-card">
        <h3>Let's Talk Gifting</h3>

        <p>
            Share your gifting requirements through our
            contact page. Mention the occasion, approximate
            quantity and preferred delivery date.
        </p>

        <a
            href="{{ route('contact') }}"
            class="gift-btn gift-btn-primary"
        >
            Send a Gifting Enquiry
        </a>

        <small>
            Our team will review your enquiry and get in touch.
        </small>
    </div>
</section>

@endsection
