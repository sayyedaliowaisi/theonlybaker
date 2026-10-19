
@extends('layouts.admin')

@section('title', 'Contact Enquiries | The Only Baker')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-contact-enquiries.css') }}">
@endpush

@section('content')

<div class="tob-enquiries-page">

    {{-- PAGE HEADER --}}
    <div class="tob-enquiries-header">

        <div>
            <span class="tob-enquiries-eyebrow">
                CUSTOMER COMMUNICATION
            </span>

            <h1>Contact Enquiries</h1>

            <p>
                Manage messages, questions and
                enquiries received from customers.
            </p>
        </div>

        <a href="{{ route('contact') }}"
           target="_blank"
           rel="noopener noreferrer"
           class="tob-enquiries-view-site">
            View Contact Page ↗
        </a>

    </div>

    {{-- STATISTICS --}}
    <div class="tob-enquiries-stats">

        <a href="{{ route('admin.contact-enquiries.index') }}"
           class="tob-enquiries-stat">

            <span>Total Enquiries</span>
            <strong>{{ $totalCount }}</strong>
            <small>All customer messages</small>

        </a>

        <a href="{{ route('admin.contact-enquiries.index', ['status' => 'unread']) }}"
           class="tob-enquiries-stat">

            <span>Unread Messages</span>
            <strong>{{ $unreadCount }}</strong>
            <small>Awaiting your attention</small>

        </a>

        <a href="{{ route('admin.contact-enquiries.index', ['status' => 'read']) }}"
           class="tob-enquiries-stat">

            <span>Read Messages</span>
            <strong>{{ $readCount }}</strong>
            <small>Previously opened enquiries</small>

        </a>

    </div>

    {{-- FILTERS --}}
    <div class="tob-enquiries-toolbar">

        <form method="GET"
              action="{{ route('admin.contact-enquiries.index') }}"
              class="tob-enquiries-search">

            <input
                type="search"
                name="search"
                value="{{ $search }}"
                placeholder="Search name, email or subject..."
                aria-label="Search contact enquiries"
            >

            <select name="status"
                    aria-label="Filter enquiry status">

                <option value="all"
                    @selected($status === 'all')>
                    All Messages
                </option>

                <option value="unread"
                    @selected($status === 'unread')>
                    Unread
                </option>

                <option value="read"
                    @selected($status === 'read')>
                    Read
                </option>

            </select>

            <button type="submit">
                Search
            </button>

            @if($search !== '' || $status !== 'all')
                <a href="{{ route('admin.contact-enquiries.index') }}"
                   class="tob-enquiries-reset">
                    Reset
                </a>
            @endif

        </form>

    </div>

    {{-- ENQUIRIES TABLE --}}
    <div class="tob-enquiries-table-card">

        <div class="tob-enquiries-table-heading">

            <h2>Customer Messages</h2>

            <span>
                {{ $enquiries->total() }} results
            </span>

        </div>

        <div class="tob-enquiries-table-scroll">

            <table class="tob-enquiries-table">

                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Subject</th>
                        <th>Received</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($enquiries as $enquiry)

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

                        <tr class="{{ !$enquiry->is_read ? 'is-unread' : '' }}">

                            {{-- CUSTOMER --}}
                            <td>
                                <div class="tob-enquiries-customer">

                                    <strong>
                                        {{ $enquiry->name }}
                                    </strong>

                                    <span>
                                        {{ $enquiry->email }}
                                    </span>

                                </div>
                            </td>

                            {{-- SUBJECT --}}
                            <td>
                                <span class="tob-enquiries-subject">
                                    {{ $subjectLabel }}
                                </span>
                            </td>

                            {{-- DATE --}}
                            <td>
                                <span class="tob-enquiries-date">
                                    {{ $enquiry->created_at->format('d M Y') }}
                                </span>

                                <small>
                                    {{ $enquiry->created_at->format('h:i A') }}
                                </small>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($enquiry->is_read)
                                    <span class="tob-enquiries-status is-read">
                                        Read
                                    </span>
                                @else
                                    <span class="tob-enquiries-status is-unread">
                                        Unread
                                    </span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td>
                                <div class="tob-enquiries-actions">

                                    <a href="{{ route('admin.contact-enquiries.show', $enquiry) }}"
                                       class="tob-enquiries-action-view">
                                        View
                                    </a>

                                    @if($enquiry->is_read)

                                        <form method="POST"
                                              action="{{ route('admin.contact-enquiries.unread', $enquiry) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="tob-enquiries-action-secondary">
                                                Mark Unread
                                            </button>
                                        </form>

                                    @else

                                        <form method="POST"
                                              action="{{ route('admin.contact-enquiries.read', $enquiry) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="tob-enquiries-action-secondary">
                                                Mark Read
                                            </button>
                                        </form>

                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5"
                                class="tob-enquiries-empty">

                                <span aria-hidden="true">✉</span>

                                <h3>No enquiries found</h3>

                                <p>
                                    Customer enquiries will appear
                                    here when received.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if($enquiries->hasPages())

            <div class="tob-enquiries-pagination">
                {{ $enquiries->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
