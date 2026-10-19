
@extends('layouts.app')

@section('title', 'Custom Orders | The Only Baker')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom-orders.css') }}">
@endpush

@section('content')

<main class="co-page">

    {{-- HERO --}}
    <section class="co-hero">
        <div class="co-hero-inner">
            <span class="co-eyebrow">MADE JUST FOR YOU</span>

            <h1>
                Your Occasion.<br>
                <em>Our Sweet Creation.</em>
            </h1>

            <p>
                From thoughtful celebrations to memorable
                gatherings, tell us what you're dreaming of.
                We'll explore how to make it extra special.
            </p>

            <a href="#custom-order-form" class="co-btn co-btn-dark">
                Enquire Now <span aria-hidden="true">↗</span>
            </a>
        </div>
    </section>

    {{-- INTRO --}}
    <section class="co-intro">
        <span class="co-eyebrow">SOMETHING PERSONAL</span>

        <h2>Every Celebration Deserves a Little Magic</h2>

        <p>
            Looking for a thoughtful gift, a personalised
            bakery selection or treats for a special event?
            Share your requirements and our team will
            review the possibilities with you.
        </p>
    </section>

    {{-- OCCASIONS --}}
    <section class="co-occasions">
        <div class="co-section-heading">
            <span class="co-eyebrow">CELEBRATE YOUR WAY</span>
            <h2>Made for Every Occasion</h2>
        </div>

        <div class="co-grid">

            <article class="co-card">
                <div class="co-card-number">01</div>
                <h3>Birthdays</h3>
                <p>
                    Sweet surprises and thoughtful treats
                    for unforgettable birthday celebrations.
                </p>
            </article>

            <article class="co-card">
                <div class="co-card-number">02</div>
                <h3>Weddings & Anniversaries</h3>
                <p>
                    Elegant gifting ideas for moments
                    worth celebrating together.
                </p>
            </article>

            <article class="co-card">
                <div class="co-card-number">03</div>
                <h3>Corporate Events</h3>
                <p>
                    Custom gifting enquiries for teams,
                    clients and business celebrations.
                </p>
            </article>

            <article class="co-card">
                <div class="co-card-number">04</div>
                <h3>Festivals & Special Days</h3>
                <p>
                    Thoughtful treats to make festive
                    moments even sweeter.
                </p>
            </article>

        </div>
    </section>

    {{-- PROCESS --}}
    <section class="co-process">
        <div class="co-section-heading">
            <span class="co-eyebrow">SIMPLE & PERSONAL</span>
            <h2>How It Works</h2>
        </div>

        <div class="co-process-grid">
            <div>
                <span>01</span>
                <h3>Tell Us Your Idea</h3>
                <p>Share your occasion, quantity and preferences.</p>
            </div>

            <div>
                <span>02</span>
                <h3>Discuss the Details</h3>
                <p>Our team will review availability and options.</p>
            </div>

            <div>
                <span>03</span>
                <h3>Confirm Your Order</h3>
                <p>Finalise the details after receiving a quotation.</p>
            </div>
        </div>
    </section>

    {{-- ENQUIRY FORM --}}
    <section class="co-enquiry" id="custom-order-form">
        <div class="co-enquiry-wrap">

            <div class="co-enquiry-info">
                <span class="co-eyebrow">LET'S CREATE SOMETHING</span>

                <h2>Tell Us About Your <em>Special Order</em></h2>

                <p>
                    Fill out the enquiry form and share as many
                    details as possible. Our team can review
                    your requirements and contact you.
                </p>

                <div class="co-info-note">
                    <strong>Please note</strong>
                    <p>
                        Submitting an enquiry does not confirm
                        an order. Product options, customisation,
                        pricing and delivery are subject to
                        confirmation.
                    </p>
                </div>
            </div>

            <div class="co-form-card">

                @if(session('success'))
                    <div class="co-alert co-alert-success" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="co-alert co-alert-error" role="alert">
                        Please review the form and try again.
                    </div>
                @endif

                {{--
                    This form sends a formatted enquiry to the
                    existing Contact Us page for submission.
                    No new backend route is required.
                --}}

                <form id="customOrderForm" novalidate>
                    <div class="co-form-grid">

                        <div class="co-field">
                            <label for="coName">Full Name *</label>
                            <input
                                id="coName"
                                name="customer_name"
                                type="text"
                                autocomplete="name"
                                required
                                placeholder="Your full name"
                            >
                        </div>

                        <div class="co-field">
                            <label for="coEmail">Email Address *</label>
                            <input
                                id="coEmail"
                                name="customer_email"
                                type="email"
                                autocomplete="email"
                                required
                                placeholder="you@example.com"
                            >
                        </div>

                        <div class="co-field">
                            <label for="coPhone">Phone Number *</label>
                            <input
                                id="coPhone"
                                name="customer_phone"
                                type="tel"
                                autocomplete="tel"
                                required
                                placeholder="Your phone number"
                            >
                        </div>

                        <div class="co-field">
                            <label for="coOccasion">Occasion *</label>
                            <select id="coOccasion" name="occasion" required>
                                <option value="">Select an occasion</option>
                                <option>Birthday</option>
                                <option>Wedding</option>
                                <option>Anniversary</option>
                                <option>Corporate Event</option>
                                <option>Festival</option>
                                <option>Other</option>
                            </select>
                        </div>

                        <div class="co-field">
                            <label for="coQuantity">Approx. Quantity</label>
                            <input
                                id="coQuantity"
                                name="quantity"
                                type="number"
                                min="1"
                                placeholder="e.g. 50"
                            >
                        </div>

                        <div class="co-field">
                            <label for="coDate">Preferred Date</label>
                            <input
                                id="coDate"
                                name="preferred_date"
                                type="date"
                                min="{{ now()->toDateString() }}"
                            >
                        </div>

                        <div class="co-field co-field-full">
                            <label for="coBudget">Approximate Budget</label>
                            <select id="coBudget" name="budget">
                                <option value="">Select a budget range</option>
                                <option>Below ₹2,000</option>
                                <option>₹2,000 – ₹5,000</option>
                                <option>₹5,000 – ₹10,000</option>
                                <option>₹10,000 – ₹25,000</option>
                                <option>Above ₹25,000</option>
                                <option>Need guidance</option>
                            </select>
                        </div>

                        <div class="co-field co-field-full">
                            <label for="coMessage">Your Requirements *</label>
                            <textarea
                                id="coMessage"
                                name="requirements"
                                rows="5"
                                required
                                placeholder="Tell us about the products, flavours, packaging, theme, delivery location or anything else you have in mind..."
                            ></textarea>
                        </div>
                    </div>

                    <button type="submit" class="co-btn co-btn-dark co-submit">
                        Continue to Enquiry
                        <span aria-hidden="true">↗</span>
                    </button>

                    <p class="co-form-footnote">
                        You'll be taken to our Contact Us page
                        to review and submit your enquiry.
                    </p>
                </form>

            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="co-bottom">
        <span class="co-eyebrow">MORE WAYS TO CELEBRATE</span>
        <h2>Looking for a Thoughtful Gift?</h2>
        <p>Explore our gifting options for special moments.</p>

        <a href="{{ route('gifting') }}" class="co-btn co-btn-dark">
            Explore Gifting <span aria-hidden="true">→</span>
        </a>
    </section>

</main>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('customOrderForm');

    if (!form) return;

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        if (!form.reportValidity()) return;

        const value = (name) => {
            const field = form.elements.namedItem(name);
            return field ? String(field.value).trim() : '';
        };

        const details = [
            'CUSTOM ORDER ENQUIRY',
            '',
            'Name: ' + value('customer_name'),
            'Email: ' + value('customer_email'),
            'Phone: ' + value('customer_phone'),
            'Occasion: ' + value('occasion'),
            'Quantity: ' + (value('quantity') || 'Not specified'),
            'Preferred Date: ' + (value('preferred_date') || 'Not specified'),
            'Budget: ' + (value('budget') || 'Not specified'),
            '',
            'Requirements:',
            value('requirements')
        ].join('\n');

        const url = new URL(@json(route('contact')));

        url.searchParams.set('subject', 'Custom Order Enquiry');
        url.searchParams.set('message', details);

        window.location.assign(url.toString());
    });
});
</script>
@endpush
