
@extends('layouts.app')

@section('title', 'Shipping & Delivery Policy | The Only Baker')

@section('meta_description', 'Read The Only Baker shipping and delivery policy, including delivery coverage, shipping charges, tracking and customer support.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/shipping-delivery.css') }}">
@endpush

@section('content')

<section class="shipping-hero">
    <div class="shipping-hero-inner">
        <span class="shipping-eyebrow">DELIVERING SWEET MOMENTS</span>

        <h1>
            Shipping & <em>Delivery</em>
        </h1>

        <p>
            Everything you need to know about how your
            favourite treats make their way to you.
        </p>

        <div class="shipping-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Shipping & Delivery</span>
        </div>
    </div>
</section>

<section class="shipping-highlights">
    <div class="shipping-highlight">
        <span class="shipping-highlight-icon">✦</span>
        <h3>Thoughtfully Packed</h3>
        <p>We aim to package every order with care.</p>
    </div>

    <div class="shipping-highlight">
        <span class="shipping-highlight-icon">♡</span>
        <h3>Made with Care</h3>
        <p>Freshly prepared treats for special moments.</p>
    </div>

    <div class="shipping-highlight">
        <span class="shipping-highlight-icon">↗</span>
        <h3>Delivery Support</h3>
        <p>Questions about your shipment? Contact our team.</p>
    </div>
</section>

<section class="shipping-policy">
    <div class="shipping-policy-container">

        <aside class="shipping-sidebar">
            <span class="shipping-sidebar-label">ON THIS PAGE</span>

            <nav aria-label="Shipping policy sections">
                <a href="#shipping-overview">Overview</a>
                <a href="#shipping-coverage">Delivery Coverage</a>
                <a href="#shipping-charges">Shipping Charges</a>
                <a href="#shipping-processing">Order Processing</a>
                <a href="#shipping-timeline">Delivery Timeline</a>
                <a href="#shipping-tracking">Order Tracking</a>
                <a href="#shipping-address">Address & Delivery Issues</a>
                <a href="#shipping-damage">Damaged Packages</a>
                <a href="#shipping-contact">Contact Us</a>
            </nav>
        </aside>

        <div class="shipping-content">

            <div class="shipping-notice">
                <strong>Policy information</strong>
                <p>
                    Delivery availability, charges and estimated
                    timelines may vary depending on your order
                    and destination. Please refer to the details
                    confirmed at checkout or by our team.
                </p>
            </div>

            <article id="shipping-overview" class="shipping-block">
                <span class="shipping-number">01</span>
                <h2>Overview</h2>

                <p>
                    At The Only Baker, we understand that every
                    order is more than a package. Whether you
                    are treating yourself or sending something
                    special, we aim to make the delivery
                    experience smooth and reliable.
                </p>
            </article>

            <article id="shipping-coverage" class="shipping-block">
                <span class="shipping-number">02</span>
                <h2>Delivery Coverage</h2>

                <p>
                    Delivery serviceability depends on the
                    destination PIN code and the shipping
                    services available for your order.
                </p>

                <p>
                    If you are unsure whether delivery is
                    available at your location, please contact
                    our team before placing an order.
                </p>
            </article>

            <article id="shipping-charges" class="shipping-block">
                <span class="shipping-number">03</span>
                <h2>Shipping Charges</h2>

                <p>
                    Applicable shipping charges, if any, will
                    be displayed during checkout or confirmed
                    before your order is finalised.
                </p>

                <div class="shipping-note">
                    <strong>Free shipping offer</strong>
                    <p>
                        Free shipping on eligible orders above
                        ₹499 is advertised on our website.
                        Applicable conditions, delivery
                        locations and any exclusions should
                        be confirmed before publishing this
                        policy as final.
                    </p>
                </div>
            </article>

            <article id="shipping-processing" class="shipping-block">
                <span class="shipping-number">04</span>
                <h2>Order Processing</h2>

                <p>
                    Orders may require preparation, packing
                    and verification before dispatch.
                    Processing times can vary based on
                    product availability, order quantity
                    and special requirements.
                </p>

                <p>
                    Bulk, festive and customised gifting
                    orders may require additional preparation
                    time, which should be discussed before
                    confirmation.
                </p>
            </article>

            <article id="shipping-timeline" class="shipping-block">
                <span class="shipping-number">05</span>
                <h2>Estimated Delivery Timeline</h2>

                <p>
                    Estimated delivery dates depend on your
                    location, courier availability and the
                    time needed to prepare your order.
                </p>

                <p>
                    Delivery estimates are not guaranteed
                    unless explicitly confirmed by our team.
                    Weather conditions, holidays, transport
                    disruptions and other circumstances
                    may affect delivery.
                </p>
            </article>

            <article id="shipping-tracking" class="shipping-block">
                <span class="shipping-number">06</span>
                <h2>Order Tracking</h2>

                <p>
                    Where tracking is available, relevant
                    shipment information may be shared
                    after dispatch.
                </p>

                <p>
                    If you need help with an existing order,
                    contact our support team and provide
                    your order reference.
                </p>
            </article>

            <article id="shipping-address" class="shipping-block">
                <span class="shipping-number">07</span>
                <h2>Address & Delivery Issues</h2>

                <p>
                    Please ensure that your name, phone
                    number, PIN code and complete delivery
                    address are accurate before placing
                    an order.
                </p>

                <p>
                    If you notice an error in your address,
                    contact us promptly. Changes may not
                    be possible after dispatch.
                </p>

                <p>
                    Delivery attempts, re-shipping and
                    undeliverable orders will be handled
                    according to the applicable courier
                    arrangements and confirmed order terms.
                </p>
            </article>

            <article id="shipping-damage" class="shipping-block">
                <span class="shipping-number">08</span>
                <h2>Damaged Packages</h2>

                <p>
                    If your package arrives damaged, please
                    contact our team as soon as possible.
                    Keep the original packaging and share
                    clear photographs of the package and
                    affected products to help us review
                    the issue.
                </p>

                <p>
                    Any replacement, refund or other
                    resolution will depend on the applicable
                    return and refund policy.
                </p>
            </article>

            <article id="shipping-contact" class="shipping-block">
                <span class="shipping-number">09</span>
                <h2>Need Delivery Assistance?</h2>

                <p>
                    Our team is here to help with questions
                    about shipping, delivery availability
                    or existing orders.
                </p>

                <a href="{{ route('contact') }}"
                   class="shipping-contact-link">
                    Contact Our Team
                    <span aria-hidden="true">↗</span>
                </a>
            </article>

        </div>
    </div>
</section>

<section class="shipping-bottom">
    <span class="shipping-eyebrow">A LITTLE SOMETHING SWEET</span>

    <h2>Ready for Your Next Treat?</h2>

    <p>
        Explore our cookie collection and discover
        something delicious.
    </p>

    <a href="{{ route('shop') }}" class="shipping-shop-btn">
        Explore Our Cookies
        <span aria-hidden="true">→</span>
    </a>
</section>

@endsection
