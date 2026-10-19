
@extends('layouts.admin')

@section('title', 'Enquiry Details | The Only Baker')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-contact-enquiries.css') }}"
    >
@endpush

@section('content')

@php
    $subjectLabels = [
        'general' => 'General Enquiry',
        'product' => 'Product Information',
        'order' => 'Order Support',
        'gifting' => 'Gifting Enquiry',
        'bulk' => 'Bulk / Corporate Orders',
        'other' => 'Other',
    ];

    $subjectLabel = $subjectLabels[$enquiry->subject]
        ?? ucfirst($enquiry->subject);
@endphp

<div class="tob-enquiry-detail">

    {{-- BACK LINK --}}
    <a
        href="{{ route('admin.contact-enquiries.index') }}"
        class="tob-enquiry-back"
    >
        ← Back to Enquiries
    </a>

    {{-- PAGE HEADER --}}
    <div class="tob-enquiry-detail-header">

        <div>
            <span class="tob-enquiries-eyebrow">
                CUSTOMER COMMUNICATION
            </span>

            <h1>Enquiry Details</h1>

            <p>
                Review customer information
                and respond to their message.
            </p>
        </div>

        <span class="tob-enquiries-status {{ $enquiry->is_read ? 'is-read' : 'is-unread' }}">
            {{ $enquiry->is_read ? 'Read' : 'Unread' }}
        </span>

    </div>

    {{-- DETAIL GRID --}}
    <div class="tob-enquiry-detail-grid">

        {{-- MAIN MESSAGE --}}
        <div class="tob-enquiry-message-card">

            <div class="tob-enquiry-card-top">

                <span class="tob-enquiries-eyebrow">
                    CUSTOMER MESSAGE
                </span>

                <h2>{{ $subjectLabel }}</h2>

                <p>
                    Received on
                    {{ $enquiry->created_at->format('d M Y, h:i A') }}
                </p>

            </div>

            <div class="tob-enquiry-message-body">
                {{ $enquiry->message }}
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="tob-enquiry-message-actions">

                <a
                    href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Re: Your Enquiry - The Only Baker') }}"
                    class="tob-enquiry-primary-btn"
                >
                    ✉ Reply via Email
                </a>

                @if($enquiry->is_read)

                    <form
                        method="POST"
                        action="{{ route('admin.contact-enquiries.unread', $enquiry) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="tob-enquiry-secondary-btn"
                        >
                            Mark as Unread
                        </button>
                    </form>

                @else

                    <form
                        method="POST"
                        action="{{ route('admin.contact-enquiries.read', $enquiry) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="tob-enquiry-secondary-btn"
                        >
                            Mark as Read
                        </button>
                    </form>

                @endif

            </div>

        </div>

        {{-- CUSTOMER INFORMATION --}}
        <aside class="tob-enquiry-customer-card">

            <span class="tob-enquiries-eyebrow">
                CUSTOMER INFORMATION
            </span>

            <h2>Contact Details</h2>

            <div class="tob-enquiry-info-list">

                <div class="tob-enquiry-info-item">
                    <span>Full Name</span>
                    <strong>{{ $enquiry->name }}</strong>
                </div>

                <div class="tob-enquiry-info-item">
                    <span>Email Address</span>

                    <a href="mailto:{{ $enquiry->email }}">
                        {{ $enquiry->email }}
                    </a>
                </div>

                <div class="tob-enquiry-info-item">
                    <span>Phone Number</span>

                    @if($enquiry->phone)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $enquiry->phone) }}">
                            {{ $enquiry->phone }}
                        </a>
                    @else
                        <strong>Not provided</strong>
                    @endif
                </div>

                <div class="tob-enquiry-info-item">
                    <span>Enquiry Type</span>
                    <strong>{{ $subjectLabel }}</strong>
                </div>

                <div class="tob-enquiry-info-item">
                    <span>Received</span>

                    <strong>
                        {{ $enquiry->created_at->format('d M Y') }}
                    </strong>
                </div>

                <div class="tob-enquiry-info-item">
                    <span>Read Status</span>

                    <strong>
                        @if($enquiry->read_at)
                            Read on
                            {{ $enquiry->read_at->format('d M Y, h:i A') }}
                        @else
                            Not yet read
                        @endif
                    </strong>
                </div>

            </div>

        </aside>

    </div>

</div>

@endsection
