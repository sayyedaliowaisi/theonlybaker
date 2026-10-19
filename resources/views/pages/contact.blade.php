
@extends('layouts.app')

@section('title', 'Contact Us | The Only Baker')

@section(
    'meta_description',
    'Get in touch with The Only Baker for cookie enquiries, gifting questions and more. We would love to hear from you.'
)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Contact Form Prefill
    |--------------------------------------------------------------------------
    | Supports:
    | - Regular Contact Us enquiries
    | - Custom Orders enquiries
    | - Gifting enquiries
    |
    | The subject field uses existing backend values.
    */

    $subjectOptions = [
        'general' => 'General Enquiry',
        'product' => 'Product Information',
        'order'   => 'Order Support',
        'gifting' => 'Gifting Enquiry',
        'bulk'    => 'Bulk / Corporate Orders',
        'other'   => 'Other',
        'custom'  => 'Custom Order Enquiry',
    ];

    $requestedSubject = request()->query('subject', '');

    $subjectAliases = [
        'Custom Order Enquiry' => 'custom',
        'Gifting Enquiry'      => 'gifting',
        'Bulk Order Enquiry'   => 'bulk',
        'Corporate Enquiry'    => 'bulk',
    ];

    $requestedSubject = $subjectAliases[$requestedSubject]
        ?? $requestedSubject;

    $selectedSubject = old('subject', $requestedSubject);

    if (!array_key_exists($selectedSubject, $subjectOptions)) {
        $selectedSubject = '';
    }

    $prefillName = old('name', request()->query('name', ''));
    $prefillEmail = old('email', request()->query('email', ''));
    $prefillPhone = old('phone', request()->query('phone', ''));
    $prefillMessage = old('message', request()->query('message', ''));
@endphp

<div class="tob-contact-page">

    {{-- =====================================================
         CONTACT US HERO
    ====================================================== --}}

    <section
        class="tob-contact-hero"
        aria-labelledby="tob-contact-title"
    >
        <div class="tob-contact-container">

            <div class="tob-contact-hero-content">

                <span class="tob-contact-eyebrow">
                    THE ONLY BAKER
                </span>

                <h1 id="tob-contact-title">
                    We'd Love to
                    <em>Hear From You.</em>
                </h1>

                <p>
                    Have a question, a sweet idea or
                    something special in mind?
                    We're always happy to connect.
                </p>

                <a
                    href="#contact-form"
                    class="tob-contact-btn"
                >
                    Get in Touch
                    <span aria-hidden="true">↗</span>
                </a>

            </div>

        </div>

        <div
            class="tob-contact-hero-decoration"
            aria-hidden="true"
        >
            ✦
        </div>

    </section>


    {{-- =====================================================
         CONTACT FORM + INFORMATION
    ====================================================== --}}

    <section
        class="tob-contact-main"
        id="contact-form"
        aria-labelledby="tob-contact-form-title"
    >
        <div class="tob-contact-container">

            <div class="tob-contact-main-grid">

                {{-- LEFT: CONTACT INFORMATION --}}
                <div class="tob-contact-info">

                    <span class="tob-contact-eyebrow">
                        LET'S CONNECT
                    </span>

                    <h2>
                        Let's Make
                        <em>Something Sweet.</em>
                    </h2>

                    <p class="tob-contact-info-intro">
                        Whether you have a question about
                        our cookies, need help with an order,
                        or want to explore gifting options,
                        we'd love to hear from you.
                    </p>

                    <div class="tob-contact-info-list">

                        {{-- EMAIL --}}
                        <div class="tob-contact-info-item">

                            <div
                                class="tob-contact-info-icon"
                                aria-hidden="true"
                            >
                                ✉
                            </div>

                            <div>
                                <h3>Email Us</h3>

                                <p>
                                    Send us your questions
                                    or enquiries.
                                </p>

                                <a
                                    href="mailto:mohammadaliraza176@gmail.com"
                                    class="tob-contact-info-pending"
                                >
                                    mohammadaliraza176@gmail.com
                                </a>
                            </div>

                        </div>


                        {{-- PHONE --}}
                        <div class="tob-contact-info-item">

                            <div
                                class="tob-contact-info-icon"
                                aria-hidden="true"
                            >
                                ☎
                            </div>

                            <div>
                                <h3>Call Us</h3>

                                <p>
                                    We'd be happy to
                                    assist you.
                                </p>

                                <span class="tob-contact-info-pending">
                                    Phone details coming soon
                                </span>
                            </div>

                        </div>


                        {{-- BUSINESS ENQUIRIES --}}
                        <div class="tob-contact-info-item">

                            <div
                                class="tob-contact-info-icon"
                                aria-hidden="true"
                            >
                                ♡
                            </div>

                            <div>
                                <h3>Gifting & Bulk Orders</h3>

                                <p>
                                    Planning a celebration
                                    or a thoughtful gift?
                                </p>

                                <a
                                    href="#tobContactForm"
                                    class="tob-contact-info-pending"
                                >
                                    Send us an enquiry using the form
                                </a>
                            </div>

                        </div>

                    </div>


                    {{-- CONTACT NOTE --}}
                    <div class="tob-contact-info-note">

                        <span aria-hidden="true">✦</span>

                        <p>
                            Sweet conversations are
                            always welcome.
                        </p>

                    </div>

                </div>


                {{-- =================================================
                     RIGHT: CONTACT FORM
                ================================================== --}}

                <div class="tob-contact-form-card">

                    <div class="tob-contact-form-heading">

                        <span class="tob-contact-eyebrow">
                            SEND A MESSAGE
                        </span>

                        <h2 id="tob-contact-form-title">
                            Drop Us a
                            <em>Little Note.</em>
                        </h2>

                        <p>
                            Fill out the form below
                            and we'll be happy to
                            hear what you have in mind.
                        </p>

                    </div>


                    {{-- SUCCESS MESSAGE --}}
                    @if(session('success'))

                        <div
                            class="tob-contact-alert tob-contact-alert-success"
                            role="status"
                        >
                            {{ session('success') }}
                        </div>

                    @endif


                    {{-- VALIDATION ERRORS --}}
                    @if($errors->any())

                        <div
                            class="tob-contact-alert tob-contact-alert-error"
                            role="alert"
                        >
                            <strong>
                                Please check the following:
                            </strong>

                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

                    @endif


                    {{-- CONTACT FORM --}}
                    <form
                        class="tob-contact-form"
                        id="tobContactForm"
                        action="{{ route('contact.store') }}"
                        method="POST"
                    >

                        @csrf


                        {{-- NAME + EMAIL --}}
                        <div class="tob-contact-form-row">

                            {{-- FULL NAME --}}
                            <div class="tob-contact-field">

                                <label for="contact_name">
                                    Full Name
                                    <span aria-hidden="true">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="contact_name"
                                    name="name"
                                    value="{{ $prefillName }}"
                                    placeholder="Your full name"
                                    autocomplete="name"
                                    maxlength="120"
                                    required
                                >

                            </div>


                            {{-- EMAIL --}}
                            <div class="tob-contact-field">

                                <label for="contact_email">
                                    Email Address
                                    <span aria-hidden="true">*</span>
                                </label>

                                <input
                                    type="email"
                                    id="contact_email"
                                    name="email"
                                    value="{{ $prefillEmail }}"
                                    placeholder="you@example.com"
                                    autocomplete="email"
                                    maxlength="255"
                                    required
                                >

                            </div>

                        </div>


                        {{-- PHONE + SUBJECT --}}
                        <div class="tob-contact-form-row">

                            {{-- PHONE --}}
                            <div class="tob-contact-field">

                                <label for="contact_phone">
                                    Phone Number

                                    <span class="tob-contact-optional">
                                        (Optional)
                                    </span>
                                </label>

                                <input
                                    type="tel"
                                    id="contact_phone"
                                    name="phone"
                                    value="{{ $prefillPhone }}"
                                    placeholder="Your phone number"
                                    autocomplete="tel"
                                    maxlength="20"
                                >

                            </div>


                            {{-- ENQUIRY TYPE --}}
                            <div class="tob-contact-field">

                                <label for="contact_subject">
                                    Enquiry Type
                                    <span aria-hidden="true">*</span>
                                </label>

                                <select
                                    id="contact_subject"
                                    name="subject"
                                    required
                                >

                                    <option
                                        value=""
                                        disabled
                                        @selected($selectedSubject === '')
                                    >
                                        Select an enquiry
                                    </option>

                                    @foreach($subjectOptions as $value => $label)
                                        <option
                                            value="{{ $value }}"
                                            @selected($selectedSubject === $value)
                                        >
                                            {{ $label }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- MESSAGE --}}
                        <div class="tob-contact-field">

                            <label for="contact_message">
                                Your Message
                                <span aria-hidden="true">*</span>
                            </label>

                            <textarea
                                id="contact_message"
                                name="message"
                                rows="6"
                                maxlength="3000"
                                placeholder="Tell us how we can help..."
                                required
                            >{{ $prefillMessage }}</textarea>

                        </div>


                        {{-- SUBMIT BUTTON --}}
                        <div class="tob-contact-form-footer">

                            <button
                                type="submit"
                                class="tob-contact-submit"
                            >
                                Send Your Message
                                <span aria-hidden="true">↗</span>
                            </button>

                            <span
                                class="sr-only"
                                id="contactSubmitStatus"
                                role="status"
                                aria-live="polite"
                            ></span>

                            <p>
                                <span aria-hidden="true">♡</span>
                                Thank you for reaching out.
                            </p>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </section>


    {{-- =====================================================
         BUSINESS HOURS + LOCATION
    ====================================================== --}}

    <section
        class="tob-contact-visit"
        id="visit-us"
        aria-labelledby="tob-contact-visit-title"
    >
        <div class="tob-contact-container">

            <div class="tob-contact-visit-heading">

                <span class="tob-contact-eyebrow">
                    FIND US & CONNECT
                </span>

                <h2 id="tob-contact-visit-title">
                    A Little More
                    <em>About Reaching Us.</em>
                </h2>

                <p>
                    Looking for our location or wondering
                    when to get in touch? Find the details
                    you need right here.
                </p>

            </div>


            <div class="tob-contact-visit-grid">

                {{-- BUSINESS HOURS CARD --}}
                <article class="tob-contact-visit-card">

                    <div
                        class="tob-contact-visit-icon"
                        aria-hidden="true"
                    >
                        ◷
                    </div>

                    <span class="tob-contact-visit-label">
                        WHEN TO REACH US
                    </span>

                    <h3>
                        Business <em>Hours.</em>
                    </h3>

                    <p class="tob-contact-visit-description">
                        We'd love to help with your
                        questions, orders and gifting
                        enquiries.
                    </p>

                    <div class="tob-contact-hours-pending">

                        <span aria-hidden="true">✦</span>

                        <p>
                            Our business hours will
                            be updated here soon.
                        </p>

                    </div>

                    <p class="tob-contact-visit-small">
                        You can send an enquiry using
                        the contact form above.
                    </p>

                </article>


                {{-- LOCATION CARD --}}
                <article class="tob-contact-visit-card">

                    <div
                        class="tob-contact-visit-icon"
                        aria-hidden="true"
                    >
                        ⌖
                    </div>

                    <span class="tob-contact-visit-label">
                        WHERE TO FIND US
                    </span>

                    <h3>
                        Our <em>Location.</em>
                    </h3>

                    <p class="tob-contact-visit-description">
                        Looking for our bakery or
                        business location? We're
                        preparing the details for you.
                    </p>

                    <div class="tob-contact-location-pending">

                        <span aria-hidden="true">✦</span>

                        <p>
                            Our address and location
                            details will be available soon.
                        </p>

                    </div>

                    <p class="tob-contact-visit-small">
                        Please contact us for
                        location-related enquiries.
                    </p>

                </article>

            </div>


            {{-- MAP AREA --}}
            <div class="tob-contact-map-section">

                <div class="tob-contact-map-info">

                    <span class="tob-contact-eyebrow">
                        VISIT THE ONLY BAKER
                    </span>

                    <h3>
                        Find Your Way
                        <em>To Something Sweet.</em>
                    </h3>

                    <p>
                        Once our location is confirmed,
                        you'll be able to find directions
                        and explore our location here.
                    </p>

                </div>


                <div
                    class="tob-contact-map-placeholder"
                    role="note"
                    aria-label="Map will be added when the business location is confirmed"
                >

                    <div class="tob-contact-map-placeholder-content">

                        <span
                            class="tob-contact-map-pin"
                            aria-hidden="true"
                        >
                            ⌖
                        </span>

                        <strong>
                            Location Map
                        </strong>

                        <p>
                            Map and directions
                            coming soon.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </section>

</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/contact.js') }}" defer></script>
@endpush
