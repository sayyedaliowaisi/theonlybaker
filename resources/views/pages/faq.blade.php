
@extends('layouts.app')

@section('title', 'Frequently Asked Questions | The Only Baker')

@section('meta_description', 'Find answers to frequently asked questions about ordering, delivery, payments, gifting and cookie care at The Only Baker.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/faq.css') }}">
@endpush

@section('content')

<section class="faq-hero">
    <span class="faq-eyebrow">HERE TO HELP</span>
    <h1>Frequently Asked <em>Questions</em></h1>
    <p>
        Everything you need to know about our cookies,
        your orders and the little details that matter.
    </p>
</section>

<section class="faq-section">
    <div class="faq-container">

        <div class="faq-toolbar">
            <div>
                <span class="faq-eyebrow">GOOD TO KNOW</span>
                <h2>How Can We Help?</h2>
            </div>

            <label class="faq-search">
                <span aria-hidden="true">⌕</span>
                <input
                    type="search"
                    id="faqSearch"
                    placeholder="Search your question..."
                    aria-label="Search frequently asked questions"
                >
            </label>
        </div>

        @php
            $faqGroups = [
                'Orders & Payments' => [
                    [
                        'q' => 'How can I place an order?',
                        'a' => 'Browse our collection, select your favourite products, add them to your cart and follow the checkout instructions.'
                    ],
                    [
                        'q' => 'Can I change or cancel my order?',
                        'a' => 'Please contact our team as soon as possible. Changes or cancellations depend on your order status and the applicable cancellation policy.'
                    ],
                    [
                        'q' => 'What payment methods do you accept?',
                        'a' => 'The available payment methods will be displayed during checkout.'
                    ],
                ],

                'Shipping & Delivery' => [
                    [
                        'q' => 'Do you deliver across India?',
                        'a' => 'Delivery availability depends on the destination and service coverage. Please check the available delivery options during checkout.'
                    ],
                    [
                        'q' => 'How long does delivery take?',
                        'a' => 'Delivery timelines depend on your location and the shipping service. Any available estimate will be shared during ordering or order confirmation.'
                    ],
                    [
                        'q' => 'How can I track my order?',
                        'a' => 'Please refer to any tracking information shared with your order, or contact our team for assistance.'
                    ],
                ],

                'Cookies & Storage' => [
                    [
                        'q' => 'How should I store my cookies?',
                        'a' => 'Keep cookies in a cool, dry place in an airtight container. Always follow any storage instructions printed on the product packaging.'
                    ],
                    [
                        'q' => 'Do your products contain allergens?',
                        'a' => 'Some products may contain ingredients such as wheat, milk, nuts or other allergens. Please review the product information and contact us if you have specific concerns.'
                    ],
                    [
                        'q' => 'Where can I find the best-before date?',
                        'a' => 'Please check the label on the product packaging for the best-before date and storage guidance.'
                    ],
                ],

                'Gifting & Bulk Orders' => [
                    [
                        'q' => 'Do you offer gifting options?',
                        'a' => 'Yes. You can explore our Gifting page for personal, festive and corporate gifting enquiries.'
                    ],
                    [
                        'q' => 'Can I enquire about bulk or corporate orders?',
                        'a' => 'Yes. Share your approximate quantity, occasion, budget and delivery requirements through our contact form.'
                    ],
                    [
                        'q' => 'Can I request customised gift packaging?',
                        'a' => 'You can share your preferred packaging requirements with our team. Availability and pricing will need to be confirmed.'
                    ],
                ],
            ];
        @endphp

        <div class="faq-layout">
            <aside class="faq-sidebar" aria-label="FAQ categories">
                <h3>Browse Topics</h3>

                @foreach ($faqGroups as $category => $questions)
                    <a href="#faq-{{ \Illuminate\Support\Str::slug($category) }}">
                        {{ $category }}
                        <span>↗</span>
                    </a>
                @endforeach
            </aside>

            <div class="faq-content" id="faqContent">
                @foreach ($faqGroups as $category => $questions)
                    <section
                        class="faq-group"
                        id="faq-{{ \Illuminate\Support\Str::slug($category) }}"
                    >
                        <h2>{{ $category }}</h2>

                        @foreach ($questions as $item)
                            <details class="faq-item">
                                <summary>
                                    <span>{{ $item['q'] }}</span>
                                    <span class="faq-plus" aria-hidden="true">+</span>
                                </summary>

                                <div class="faq-answer">
                                    <p>{{ $item['a'] }}</p>
                                </div>
                            </details>
                        @endforeach
                    </section>
                @endforeach

                <p class="faq-no-results" id="faqNoResults" hidden>
                    No matching questions found. Try another search.
                </p>
            </div>
        </div>

    </div>
</section>

<section class="faq-help">
    <span class="faq-eyebrow">STILL HAVE QUESTIONS?</span>
    <h2>We're Happy to Help</h2>
    <p>
        Couldn't find what you were looking for?
        Our team would love to hear from you.
    </p>

    <a href="{{ route('contact') }}" class="faq-contact-btn">
        Contact Us <span aria-hidden="true">↗</span>
    </a>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('faqSearch');
    const groups = document.querySelectorAll('.faq-group');
    const noResults = document.getElementById('faqNoResults');

    if (!search) return;

    search.addEventListener('input', function () {
        const term = this.value.trim().toLowerCase();
        let visibleCount = 0;

        groups.forEach(function (group) {
            let groupCount = 0;

            group.querySelectorAll('.faq-item').forEach(function (item) {
                const matches = item.textContent.toLowerCase().includes(term);

                item.hidden = !matches;

                if (!matches) item.open = false;

                if (matches) {
                    groupCount++;
                    visibleCount++;
                }
            });

            group.hidden = groupCount === 0;
        });

        noResults.hidden = visibleCount !== 0;
    });

    document.querySelectorAll('.faq-item').forEach(function (item) {
        item.addEventListener('toggle', function () {
            if (!item.open) return;

            document.querySelectorAll('.faq-item[open]').forEach(function (other) {
                if (other !== item) other.open = false;
            });
        });
    });
});
</script>
@endpush
