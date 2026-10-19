
@extends('layouts.app')

@section('title', 'Terms & Conditions | The Only Baker')

@section('meta_description', 'Read the Terms and Conditions for using The Only Baker website, placing orders, payments, delivery, gifting and customer support.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/terms-conditions.css') }}">
@endpush

@section('content')

<section class="terms-hero">
    <div class="terms-hero-inner">
        <span class="terms-eyebrow">THE FINE DETAILS</span>

        <h1>Terms & <em>Conditions</em></h1>

        <p>
            A little clarity goes a long way.
            Here's some important information about
            shopping with The Only Baker.
        </p>

        <nav class="terms-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Terms & Conditions</span>
        </nav>
    </div>
</section>

<section class="terms-intro">
    <div class="terms-intro-inner">
        <span class="terms-eyebrow">WELCOME TO THE ONLY BAKER</span>

        <h2>Made with Care. Shared with Trust.</h2>

        <p>
            We want your experience with us to be
            simple, enjoyable and transparent.
            These terms explain the general conditions
            that may apply when you use our website
            or purchase our products.
        </p>
    </div>
</section>

<section class="terms-policy">
    <div class="terms-container">

        <aside class="terms-sidebar">
            <span class="terms-sidebar-label">ON THIS PAGE</span>

            <nav aria-label="Terms and conditions sections">
                <a href="#terms-acceptance">Acceptance of Terms</a>
                <a href="#terms-eligibility">Account & Eligibility</a>
                <a href="#terms-products">Products & Availability</a>
                <a href="#terms-pricing">Pricing & Payments</a>
                <a href="#terms-orders">Orders & Confirmation</a>
                <a href="#terms-delivery">Shipping & Delivery</a>
                <a href="#terms-cancellation">Returns & Cancellations</a>
                <a href="#terms-gifting">Gifting & Custom Orders</a>
                <a href="#terms-usage">Website Usage</a>
                <a href="#terms-intellectual">Intellectual Property</a>
                <a href="#terms-liability">Liability</a>
                <a href="#terms-privacy">Privacy</a>
                <a href="#terms-changes">Changes to Terms</a>
                <a href="#terms-law">Applicable Law</a>
                <a href="#terms-contact">Contact Us</a>
            </nav>
        </aside>

        <div class="terms-content">

            <div class="terms-notice">
                <strong>Important information</strong>
                <p>
                    These are proposed website terms.
                    Business-specific conditions, customer
                    obligations and legal provisions must
                    be reviewed and approved before this
                    document becomes the final Terms
                    & Conditions.
                </p>
            </div>

            <article class="terms-block" id="terms-acceptance">
                <span class="terms-number">01</span>
                <h2>Acceptance of Terms</h2>

                <p>
                    These Terms & Conditions are intended
                    to describe the conditions governing
                    the use of The Only Baker website
                    and related services.
                </p>

                <p>
                    Once finalised and published, customers
                    should be able to review these terms
                    before completing a purchase.
                </p>
            </article>

            <article class="terms-block" id="terms-eligibility">
                <span class="terms-number">02</span>
                <h2>Accounts & Eligibility</h2>

                <p>
                    Certain website features may require
                    customers to create an account
                    or provide contact information.
                </p>

                <p>
                    Customers should provide accurate
                    information and take reasonable
                    steps to protect their account
                    credentials.
                </p>

                <p>
                    Any age or eligibility requirements
                    should be established in accordance
                    with applicable law.
                </p>
            </article>

            <article class="terms-block" id="terms-products">
                <span class="terms-number">03</span>
                <h2>Products & Availability</h2>

                <p>
                    We aim to present our products,
                    descriptions and images accurately.
                    However, product appearance,
                    packaging and availability may vary.
                </p>

                <p>
                    Product listings should include
                    relevant information such as
                    ingredients, allergens, quantity,
                    storage guidance and applicable
                    best-before details.
                </p>

                <p>
                    Availability may change due to
                    production capacity, seasonal demand
                    or stock limitations.
                </p>
            </article>

            <article class="terms-block" id="terms-pricing">
                <span class="terms-number">04</span>
                <h2>Pricing & Payments</h2>

                <p>
                    Product prices, applicable taxes,
                    shipping charges and other fees
                    should be clearly communicated
                    during the purchase process.
                </p>

                <p>
                    Payment methods available to customers
                    will depend on the checkout options
                    supported by our website.
                </p>

                <p>
                    Any pricing error or payment-related
                    issue will be reviewed in accordance
                    with the applicable order terms
                    and legal requirements.
                </p>
            </article>

            <article class="terms-block" id="terms-orders">
                <span class="terms-number">05</span>
                <h2>Orders & Confirmation</h2>

                <p>
                    When placing an order, customers
                    should review their selected products,
                    quantities, delivery address
                    and payment information carefully.
                </p>

                <p>
                    An order confirmation may be issued
                    after the order has been received
                    or accepted, depending on the
                    implemented checkout process.
                </p>

                <p>
                    Any conditions under which an order
                    may be declined or cancelled should
                    be clearly communicated.
                </p>
            </article>

            <article class="terms-block" id="terms-delivery">
                <span class="terms-number">06</span>
                <h2>Shipping & Delivery</h2>

                <p>
                    Delivery availability, charges
                    and timelines depend on the order
                    and destination.
                </p>

                <p>
                    For more details, please refer
                    to our Shipping & Delivery Policy.
                </p>

                <a
                    href="{{ route('shipping.delivery') }}"
                    class="terms-text-link"
                >
                    View Shipping & Delivery Policy ↗
                </a>
            </article>

            <article class="terms-block" id="terms-cancellation">
                <span class="terms-number">07</span>
                <h2>Returns, Refunds & Cancellations</h2>

                <p>
                    Return, refund and cancellation
                    requests will be handled according
                    to the applicable policy and
                    legal requirements.
                </p>

                <p>
                    Since bakery products may be
                    perishable, some orders can
                    require special consideration.
                </p>

                <a
                    href="{{ route('refund.policy') }}"
                    class="terms-text-link"
                >
                    View Return & Refund Policy ↗
                </a>
            </article>

            <article class="terms-block" id="terms-gifting">
                <span class="terms-number">08</span>
                <h2>Gifting & Custom Orders</h2>

                <p>
                    Customised gifts, corporate orders,
                    bulk orders and festive collections
                    may require additional preparation
                    time and individual confirmation.
                </p>

                <p>
                    Final specifications, pricing,
                    payment arrangements and delivery
                    requirements should be agreed upon
                    before confirming such orders.
                </p>

                <a
                    href="{{ route('gifting') }}"
                    class="terms-text-link"
                >
                    Explore Gifting Options ↗
                </a>
            </article>

            <article class="terms-block" id="terms-usage">
                <span class="terms-number">09</span>
                <h2>Acceptable Website Usage</h2>

                <p>
                    Users are expected to access
                    the website responsibly and
                    avoid activities that may
                    compromise its security,
                    availability or operation.
                </p>

                <p>
                    Unauthorised attempts to access
                    restricted systems, misuse
                    website features or interfere
                    with other users may result
                    in appropriate action.
                </p>
            </article>

            <article class="terms-block" id="terms-intellectual">
                <span class="terms-number">10</span>
                <h2>Intellectual Property</h2>

                <p>
                    Website content, including original
                    text, product photography, graphics,
                    branding and design elements,
                    may be protected by applicable
                    intellectual property laws.
                </p>

                <p>
                    Any restrictions on copying,
                    reproducing or commercially
                    using such materials should
                    reflect the rights actually
                    held by the business.
                </p>
            </article>

            <article class="terms-block" id="terms-liability">
                <span class="terms-number">11</span>
                <h2>Liability & Disclaimers</h2>

                <p>
                    We aim to maintain accurate website
                    information and reliable services.
                    However, technical interruptions,
                    third-party service issues
                    or other disruptions may occur.
                </p>

                <p>
                    Any limitation of liability
                    must comply with applicable
                    consumer protection laws
                    and cannot exclude rights
                    that cannot legally be waived.
                </p>
            </article>

            <article class="terms-block" id="terms-privacy">
                <span class="terms-number">12</span>
                <h2>Privacy & Personal Information</h2>

                <p>
                    Our handling of personal information
                    is described in our Privacy Policy.
                </p>

                <a
                    href="{{ route('privacy.policy') }}"
                    class="terms-text-link"
                >
                    Read Privacy Policy ↗
                </a>
            </article>

            <article class="terms-block" id="terms-changes">
                <span class="terms-number">13</span>
                <h2>Changes to These Terms</h2>

                <p>
                    These terms may be updated to
                    reflect changes in our website,
                    products, services or legal
                    requirements.
                </p>

                <p>
                    Any revised version should
                    clearly indicate its effective
                    date and be made accessible
                    on this page.
                </p>
            </article>

            <article class="terms-block" id="terms-law">
                <span class="terms-number">14</span>
                <h2>Applicable Law</h2>

                <p>
                    Applicable law, dispute resolution
                    arrangements and jurisdiction
                    provisions should be finalised
                    based on the business's registered
                    details and relevant legal
                    requirements.
                </p>

                <p>
                    Nothing in these terms is intended
                    to remove mandatory rights
                    available to consumers under
                    applicable law.
                </p>
            </article>

            <article class="terms-block" id="terms-contact">
                <span class="terms-number">15</span>
                <h2>Questions About These Terms?</h2>

                <p>
                    If you need clarification about
                    our website, products or ordering
                    process, please contact our team.
                </p>

                <a
                    href="{{ route('contact') }}"
                    class="terms-contact-btn"
                >
                    Contact Our Team
                    <span aria-hidden="true">↗</span>
                </a>
            </article>

        </div>
    </div>
</section>

<section class="terms-bottom">
    <span class="terms-eyebrow">BAKED WITH LOVE</span>

    <h2>A Little Sweetness Goes a Long Way</h2>

    <p>
        Discover treats made for life's
        sweetest moments.
    </p>

    <a href="{{ route('shop') }}" class="terms-shop-btn">
        Explore Our Cookies
        <span aria-hidden="true">→</span>
    </a>
</section>

@endsection
