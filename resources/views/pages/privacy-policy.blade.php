
@extends('layouts.app')

@section('title', 'Privacy Policy | The Only Baker')

@section('meta_description', 'Learn how The Only Baker collects, uses, stores and protects personal information when you browse, shop or contact us.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/privacy-policy.css') }}">
@endpush

@section('content')

<section class="privacy-hero">
    <div class="privacy-hero-inner">
        <span class="privacy-eyebrow">YOUR TRUST MATTERS</span>

        <h1>Privacy <em>Policy</em></h1>

        <p>
            We believe your personal information deserves
            thoughtful care, just like everything we bake.
        </p>

        <nav class="privacy-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Privacy Policy</span>
        </nav>
    </div>
</section>

<section class="privacy-highlights">
    <div class="privacy-highlight">
        <span aria-hidden="true">♡</span>
        <h3>Respect for Privacy</h3>
        <p>Your personal information matters to us.</p>
    </div>

    <div class="privacy-highlight">
        <span aria-hidden="true">✦</span>
        <h3>Thoughtful Practices</h3>
        <p>We aim to handle information responsibly.</p>
    </div>

    <div class="privacy-highlight">
        <span aria-hidden="true">↗</span>
        <h3>Privacy Assistance</h3>
        <p>Contact us with privacy-related questions.</p>
    </div>
</section>

<section class="privacy-policy">
    <div class="privacy-container">

        <aside class="privacy-sidebar">
            <span class="privacy-sidebar-label">ON THIS PAGE</span>

            <nav aria-label="Privacy policy sections">
                <a href="#privacy-introduction">Introduction</a>
                <a href="#privacy-information">Information We Collect</a>
                <a href="#privacy-usage">How We Use Information</a>
                <a href="#privacy-payments">Payments & Transactions</a>
                <a href="#privacy-sharing">Information Sharing</a>
                <a href="#privacy-cookies">Cookies & Analytics</a>
                <a href="#privacy-marketing">Marketing Communications</a>
                <a href="#privacy-security">Data Security</a>
                <a href="#privacy-retention">Data Retention</a>
                <a href="#privacy-rights">Your Privacy Choices</a>
                <a href="#privacy-children">Children's Privacy</a>
                <a href="#privacy-updates">Policy Updates</a>
                <a href="#privacy-contact">Contact Us</a>
            </nav>
        </aside>

        <div class="privacy-content">

            <div class="privacy-notice">
                <strong>Privacy information</strong>
                <p>
                    This policy describes the intended privacy
                    practices of The Only Baker. Specific
                    processing activities and third-party
                    services must be verified before this
                    document is published as the final policy.
                </p>
            </div>

            <article class="privacy-block" id="privacy-introduction">
                <span class="privacy-number">01</span>
                <h2>Introduction</h2>

                <p>
                    Welcome to The Only Baker. We value the
                    trust you place in us when visiting our
                    website, creating an account, purchasing
                    products or contacting our team.
                </p>

                <p>
                    This Privacy Policy explains the categories
                    of personal information that may be
                    collected and how that information may
                    be used in connection with our services.
                </p>
            </article>

            <article class="privacy-block" id="privacy-information">
                <span class="privacy-number">02</span>
                <h2>Information We Collect</h2>

                <p>
                    Depending on how you interact with our
                    website, we may collect information such as:
                </p>

                <ul>
                    <li>Name and contact details</li>
                    <li>Email address and mobile number</li>
                    <li>Billing and delivery addresses</li>
                    <li>Account and order information</li>
                    <li>Product preferences and wishlist activity</li>
                    <li>Customer support messages and enquiries</li>
                    <li>Technical information relating to website usage</li>
                </ul>

                <p>
                    We may also receive information when you
                    submit gifting, retailer or other
                    business-related enquiries.
                </p>
            </article>

            <article class="privacy-block" id="privacy-usage">
                <span class="privacy-number">03</span>
                <h2>How We Use Your Information</h2>

                <p>
                    Personal information may be used for
                    legitimate website and business purposes,
                    including:
                </p>

                <ul>
                    <li>Creating and managing customer accounts</li>
                    <li>Processing and fulfilling orders</li>
                    <li>Communicating about purchases and deliveries</li>
                    <li>Responding to enquiries and support requests</li>
                    <li>Maintaining website functionality and security</li>
                    <li>Improving products and customer experience</li>
                    <li>Meeting applicable legal obligations</li>
                </ul>
            </article>

            <article class="privacy-block" id="privacy-payments">
                <span class="privacy-number">04</span>
                <h2>Payments & Transactions</h2>

                <p>
                    Payments may be processed through payment
                    service providers made available at
                    checkout. The information collected
                    and processed by those providers will
                    depend on the payment method selected.
                </p>

                <p>
                    Please review the privacy information
                    provided by the relevant payment service
                    provider when making a transaction.
                </p>
            </article>

            <article class="privacy-block" id="privacy-sharing">
                <span class="privacy-number">05</span>
                <h2>Sharing of Information</h2>

                <p>
                    Information may need to be shared with
                    service providers that support website
                    operations, payment processing,
                    order fulfilment, shipping or customer
                    communications.
                </p>

                <p>
                    We may also disclose information when
                    required under applicable law or to
                    respond to lawful requests.
                </p>

                <p>
                    The categories of service providers and
                    the purposes of sharing should be
                    confirmed against our actual operations.
                </p>
            </article>

            <article class="privacy-block" id="privacy-cookies">
                <span class="privacy-number">06</span>
                <h2>Cookies & Website Analytics</h2>

                <p>
                    Our website may use cookies or similar
                    technologies to support essential
                    functionality, such as maintaining
                    sessions and remembering certain
                    preferences.
                </p>

                <p>
                    Additional analytics or marketing
                    technologies, if enabled, should be
                    described here along with any
                    applicable consent options.
                </p>
            </article>

            <article class="privacy-block" id="privacy-marketing">
                <span class="privacy-number">07</span>
                <h2>Marketing Communications</h2>

                <p>
                    If you choose to receive promotional
                    communications, we may use your contact
                    information to share product updates,
                    offers or announcements, subject to
                    applicable requirements.
                </p>

                <p>
                    Where marketing subscriptions are
                    available, you should be able to
                    withdraw your consent or unsubscribe
                    through the provided method.
                </p>
            </article>

            <article class="privacy-block" id="privacy-security">
                <span class="privacy-number">08</span>
                <h2>Data Security</h2>

                <p>
                    We aim to use appropriate technical
                    and organisational measures to help
                    protect personal information against
                    unauthorised access, alteration,
                    disclosure or loss.
                </p>

                <p>
                    No website or electronic transmission
                    can be guaranteed to be completely secure.
                </p>
            </article>

            <article class="privacy-block" id="privacy-retention">
                <span class="privacy-number">09</span>
                <h2>Data Retention</h2>

                <p>
                    Personal information should be retained
                    only for periods necessary to fulfil
                    relevant purposes, comply with legal
                    obligations, resolve disputes or
                    maintain appropriate business records.
                </p>

                <p>
                    Specific retention periods will depend
                    on the category of information and
                    applicable requirements.
                </p>
            </article>

            <article class="privacy-block" id="privacy-rights">
                <span class="privacy-number">10</span>
                <h2>Your Privacy Choices</h2>

                <p>
                    Depending on applicable law, you may
                    have rights relating to access,
                    correction, updating, withdrawal
                    of consent or deletion of certain
                    personal information.
                </p>

                <p>
                    To make a privacy-related request,
                    please contact us using the details
                    provided below. Requests will be
                    reviewed in accordance with
                    applicable requirements.
                </p>
            </article>

            <article class="privacy-block" id="privacy-children">
                <span class="privacy-number">11</span>
                <h2>Children's Privacy</h2>

                <p>
                    Where personal information relating
                    to children is processed, applicable
                    legal requirements and appropriate
                    safeguards must be followed.
                </p>
            </article>

            <article class="privacy-block" id="privacy-updates">
                <span class="privacy-number">12</span>
                <h2>Changes to This Policy</h2>

                <p>
                    We may update this Privacy Policy
                    to reflect changes in our website,
                    services, business practices or
                    legal requirements.
                </p>

                <p>
                    The updated version will be made
                    available on this page with an
                    appropriate effective date.
                </p>
            </article>

            <article class="privacy-block" id="privacy-contact">
                <span class="privacy-number">13</span>
                <h2>Privacy Questions?</h2>

                <p>
                    If you have questions about how
                    personal information is handled,
                    or wish to submit a privacy-related
                    request, please contact our team.
                </p>

                <a
                    href="{{ route('contact') }}"
                    class="privacy-action-btn"
                >
                    Contact Our Team
                    <span aria-hidden="true">↗</span>
                </a>
            </article>

        </div>
    </div>
</section>

<section class="privacy-bottom">
    <span class="privacy-eyebrow">THANK YOU FOR YOUR TRUST</span>

    <h2>Thoughtfully Made, Thoughtfully Shared</h2>

    <p>
        We're grateful to be part of your sweetest moments.
    </p>

    <a href="{{ route('shop') }}" class="privacy-shop-btn">
        Explore Our Cookies
        <span aria-hidden="true">→</span>
    </a>
</section>

@endsection
