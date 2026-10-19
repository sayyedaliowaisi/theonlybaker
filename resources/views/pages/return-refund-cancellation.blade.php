
@extends('layouts.app')

@section('title', 'Return, Refund & Cancellation Policy | The Only Baker')

@section('meta_description', 'Read The Only Baker return, refund and cancellation policy, including damaged products, incorrect orders and refund requests.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/refund-policy.css') }}">
@endpush

@section('content')

<section class="refund-hero">
    <div class="refund-hero-inner">
        <span class="refund-eyebrow">SHOP WITH CONFIDENCE</span>

        <h1>
            Returns, Refunds
            <em>& Cancellations</em>
        </h1>

        <p>
            Your experience matters to us. Here is some
            helpful information about order changes,
            product concerns and refund requests.
        </p>

        <nav class="refund-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Return & Refund Policy</span>
        </nav>
    </div>
</section>

<section class="refund-highlights">
    <div class="refund-highlight">
        <span class="refund-highlight-icon">♡</span>
        <h3>Customer Care</h3>
        <p>We're here to listen to your concerns.</p>
    </div>

    <div class="refund-highlight">
        <span class="refund-highlight-icon">✦</span>
        <h3>Product Quality</h3>
        <p>We aim to deliver products with care.</p>
    </div>

    <div class="refund-highlight">
        <span class="refund-highlight-icon">↗</span>
        <h3>Easy Assistance</h3>
        <p>Contact our team if something isn't right.</p>
    </div>
</section>

<section class="refund-policy">
    <div class="refund-policy-container">

        <aside class="refund-sidebar">
            <span class="refund-sidebar-label">ON THIS PAGE</span>

            <nav aria-label="Policy sections">
                <a href="#refund-overview">Overview</a>
                <a href="#refund-returns">Product Returns</a>
                <a href="#refund-cancellations">Order Cancellations</a>
                <a href="#refund-damaged">Damaged Products</a>
                <a href="#refund-incorrect">Incorrect Orders</a>
                <a href="#refund-request">Requesting a Refund</a>
                <a href="#refund-processing">Refund Processing</a>
                <a href="#refund-gifting">Gifting & Bulk Orders</a>
                <a href="#refund-contact">Customer Support</a>
            </nav>
        </aside>

        <div class="refund-content">

            <div class="refund-notice">
                <strong>Important information</strong>
                <p>
                    This page explains our proposed process
                    for handling order concerns. Specific
                    eligibility conditions, reporting periods
                    and refund timelines will be finalised
                    before the policy is published.
                </p>
            </div>

            <article class="refund-block" id="refund-overview">
                <span class="refund-number">01</span>
                <h2>Our Commitment</h2>

                <p>
                    At The Only Baker, we want every order
                    to bring a little happiness.
                    If something goes wrong with your order,
                    please get in touch so our team can
                    understand the issue and review
                    the available options.
                </p>
            </article>

            <article class="refund-block" id="refund-returns">
                <span class="refund-number">02</span>
                <h2>Product Returns</h2>

                <p>
                    Our products may include freshly baked
                    and other food items that require
                    appropriate handling and storage.
                    Because of their nature, returns may
                    not always be practical or suitable.
                </p>

                <p>
                    Return requests will be reviewed based
                    on the product, order circumstances,
                    applicable policy and legal requirements.
                    Please contact us before attempting
                    to send any food product back.
                </p>
            </article>

            <article class="refund-block" id="refund-cancellations">
                <span class="refund-number">03</span>
                <h2>Order Cancellations</h2>

                <p>
                    If you need to cancel an order,
                    please contact us as soon as possible
                    with your order details.
                </p>

                <p>
                    Cancellation availability may depend
                    on whether preparation, packing or
                    dispatch has started.
                </p>

                <div class="refund-note">
                    <strong>Before requesting cancellation</strong>
                    <p>
                        Keep your order number ready.
                        Any applicable cancellation charges
                        or restrictions should be clearly
                        communicated before confirmation.
                    </p>
                </div>
            </article>

            <article class="refund-block" id="refund-damaged">
                <span class="refund-number">04</span>
                <h2>Damaged or Defective Products</h2>

                <p>
                    If you receive a damaged, defective
                    or otherwise concerning product,
                    please contact our team promptly.
                </p>

                <p>
                    To help us review your request,
                    you may be asked to provide:
                </p>

                <ul>
                    <li>Your order number</li>
                    <li>Photographs of the product</li>
                    <li>Photographs of the packaging</li>
                    <li>A brief description of the issue</li>
                </ul>

                <p>
                    Please retain the original packaging
                    where reasonably possible until the
                    issue has been reviewed.
                </p>
            </article>

            <article class="refund-block" id="refund-incorrect">
                <span class="refund-number">05</span>
                <h2>Incorrect or Missing Items</h2>

                <p>
                    If your delivery contains an incorrect
                    product or an item is missing,
                    contact our team with your order
                    reference and a description of
                    what you received.
                </p>

                <p>
                    We will review the order information
                    and discuss an appropriate resolution.
                </p>
            </article>

            <article class="refund-block" id="refund-request">
                <span class="refund-number">06</span>
                <h2>How to Request a Refund</h2>

                <p>
                    You can submit a refund-related enquiry
                    through our Contact Us page.
                </p>

                <div class="refund-steps">
                    <div>
                        <span>01</span>
                        <h3>Contact Our Team</h3>
                        <p>Explain the issue with your order.</p>
                    </div>

                    <div>
                        <span>02</span>
                        <h3>Share Details</h3>
                        <p>Provide the order reference and relevant evidence.</p>
                    </div>

                    <div>
                        <span>03</span>
                        <h3>Request Review</h3>
                        <p>Our team will assess the concern and respond.</p>
                    </div>
                </div>

                <a
                    href="{{ route('contact') }}"
                    class="refund-action-btn"
                >
                    Submit a Request
                    <span aria-hidden="true">↗</span>
                </a>
            </article>

            <article class="refund-block" id="refund-processing">
                <span class="refund-number">07</span>
                <h2>Refund Processing</h2>

                <p>
                    Where a refund is approved, the method
                    and expected processing period will
                    depend on the original payment method,
                    payment provider and applicable terms.
                </p>

                <p>
                    Our team will share relevant refund
                    information after the request
                    has been reviewed.
                </p>
            </article>

            <article class="refund-block" id="refund-gifting">
                <span class="refund-number">08</span>
                <h2>Gifting & Bulk Orders</h2>

                <p>
                    Customised gifts, corporate orders
                    and large-quantity purchases may
                    involve special preparation or
                    packaging requirements.
                </p>

                <p>
                    Cancellation and refund conditions
                    for these orders should be confirmed
                    during the quotation or order
                    confirmation process.
                </p>
            </article>

            <article class="refund-block" id="refund-contact">
                <span class="refund-number">09</span>
                <h2>We're Here to Help</h2>

                <p>
                    If you have questions about an order,
                    a cancellation or a refund request,
                    our support team can assist you.
                </p>

                <a
                    href="{{ route('contact') }}"
                    class="refund-action-btn"
                >
                    Contact Customer Support
                    <span aria-hidden="true">↗</span>
                </a>
            </article>

        </div>
    </div>
</section>

<section class="refund-bottom">
    <span class="refund-eyebrow">A SWEETER EXPERIENCE</span>

    <h2>Explore Something Delicious</h2>

    <p>
        Discover our collection of cookies,
        thoughtfully crafted for everyday joy.
    </p>

    <a href="{{ route('shop') }}" class="refund-shop-btn">
        Explore Our Cookies
        <span aria-hidden="true">→</span>
    </a>
</section>

@endsection
