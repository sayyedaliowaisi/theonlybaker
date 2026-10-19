
@extends('layouts.admin')

@section('title', 'Email Notifications | The Only Baker')

@push('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/admin-email-notifications.css') }}"
>
@endpush

@section('content')

<div class="tob-email-admin">

    {{-- =====================================================
         PAGE HEADING
    ====================================================== --}}

    <div class="tob-email-heading">

        <div>
            <span class="tob-email-eyebrow">
                STORE COMMUNICATIONS
            </span>

            <h1>Email Notifications</h1>

            <p>
                Monitor new order emails, customer status updates,
                COD payment confirmations and delivery errors.
            </p>
        </div>

        <a
            href="{{ route('admin.email-notifications.index') }}"
            class="tob-email-refresh"
        >
            ↻ Refresh
        </a>

    </div>

    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div
            class="tob-email-investigation-warning"
            role="alert"
        >
            <strong>Please review the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

    @endif

    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div
            class="tob-email-investigation-card"
            role="status"
        >
            <strong>{{ session('success') }}</strong>
        </div>

    @endif

    {{-- =====================================================
         STUCK PROCESSING WARNING
    ====================================================== --}}

    @if(($stuckCount ?? 0) > 0)

        <div class="tob-email-investigation-warning">

            <strong>
                ⚠ {{ $stuckCount }}
                notification(s) require investigation
            </strong>

            <p>
                Some emails have remained in Processing for
                more than five minutes. Review their details
                and verify the delivery outcome before
                attempting recovery.
            </p>

        </div>

    @endif

    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="tob-email-stats">

        <div class="tob-email-stat">
            <span>Total Notifications</span>
            <strong>
                {{ number_format($stats['total'] ?? 0) }}
            </strong>
        </div>

        <div class="tob-email-stat">
            <span>Pending</span>
            <strong>
                {{ number_format($stats['pending'] ?? 0) }}
            </strong>
        </div>

        <div class="tob-email-stat">
            <span>Processing</span>
            <strong>
                {{ number_format($stats['processing'] ?? 0) }}
            </strong>
        </div>

        <div class="tob-email-stat">
            <span>Sent</span>
            <strong>
                {{ number_format($stats['sent'] ?? 0) }}
            </strong>
        </div>

        <div class="tob-email-stat">
            <span>Failed</span>
            <strong>
                {{ number_format($stats['failed'] ?? 0) }}
            </strong>
        </div>

        <div class="tob-email-stat">
            <span>Delivery Uncertain</span>
            <strong>
                {{ number_format($stats['delivery_uncertain'] ?? 0) }}
            </strong>
        </div>

    </div>

    {{-- =====================================================
         SEARCH AND FILTERS
    ====================================================== --}}

    <form
        method="GET"
        action="{{ route('admin.email-notifications.index') }}"
        class="tob-email-filters"
    >

        {{-- SEARCH --}}

        <div class="tob-email-filter-field tob-email-search">

            <label for="emailSearch">
                Search Notifications
            </label>

            <input
                type="search"
                id="emailSearch"
                name="search"
                value="{{ request('search') }}"
                placeholder="Order number, name or email"
                maxlength="150"
            >

        </div>

        {{-- STATUS FILTER --}}

        <div class="tob-email-filter-field">

            <label for="emailStatus">
                Status
            </label>

            <select
                id="emailStatus"
                name="status"
            >

                <option value="">
                    All Statuses
                </option>

                @foreach([
                    'pending',
                    'processing',
                    'sent',
                    'failed',
                    'delivery_uncertain',
                ] as $status)

                    <option
                        value="{{ $status }}"
                        @selected(request('status') === $status)
                    >
                        {{ ucwords(str_replace('_', ' ', $status)) }}
                    </option>

                @endforeach

            </select>

        </div>

        {{-- EMAIL TYPE FILTER --}}

        <div class="tob-email-filter-field">

            <label for="emailType">
                Email Type
            </label>

            <select
                id="emailType"
                name="type"
            >

                <option value="">
                    All Types
                </option>

                @foreach([
                    'customer' => 'Customer Order Confirmation',
                    'admin' => 'Admin New Order',
                    'order_confirmed' => 'Order Confirmed',
                    'order_shipped' => 'Order Shipped',
                    'order_delivered' => 'Order Delivered',
                    'order_cancelled' => 'Order Cancelled',
                    'payment_received' => 'Payment Received',
                ] as $type => $label)

                    <option
                        value="{{ $type }}"
                        @selected(request('type') === $type)
                    >
                        {{ $label }}
                    </option>

                @endforeach

            </select>

        </div>

        {{-- FILTER ACTIONS --}}

        <div class="tob-email-filter-actions">

            <button type="submit">
                Apply Filters
            </button>

            <a href="{{ route('admin.email-notifications.index') }}">
                Reset
            </a>

        </div>

    </form>

    {{-- =====================================================
         NOTIFICATIONS TABLE
    ====================================================== --}}

    <div class="tob-email-table-card">

        <div class="tob-email-table-header">

            <div>
                <h2>Notification History</h2>

                <p>
                    {{ number_format($notifications->total()) }}
                    matching notification(s)
                </p>
            </div>

        </div>

        <div class="tob-email-table-scroll">

            <table class="tob-email-table">

                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Recipient</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Attempts</th>
                        <th>Created</th>
                        <th>Sent At</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($notifications as $notification)

                        @php
                            $isStuck = $notification->isStuck();

                            $typeLabels = [
                                'customer' => 'Customer Order Confirmation',
                                'admin' => 'Admin New Order',
                                'order_confirmed' => 'Order Confirmed',
                                'order_shipped' => 'Order Shipped',
                                'order_delivered' => 'Order Delivered',
                                'order_cancelled' => 'Order Cancelled',
                                'payment_received' => 'Payment Received',
                            ];

                            $typeLabel = $typeLabels[$notification->type]
                                ?? ucwords(str_replace('_', ' ', $notification->type));
                        @endphp

                        <tr>

                            {{-- ORDER DETAILS --}}

                            <td>

                                @if($notification->order)

                                    <a
                                        class="tob-email-order-link"
                                        href="{{ route(
                                            'admin.orders.show',
                                            $notification->order_id
                                        ) }}"
                                    >
                                        {{ $notification->order->order_number }}
                                    </a>

                                    <small>
                                        {{ $notification->order->name }}
                                    </small>

                                @else

                                    <span>
                                        Order unavailable
                                    </span>

                                @endif

                            </td>

                            {{-- RECIPIENT --}}

                            <td>

                                <span class="tob-email-recipient">
                                    {{ $notification->recipient_email }}
                                </span>

                            </td>

                            {{-- EMAIL TYPE --}}

                            <td>

                                <span class="tob-email-type">
                                    {{ $typeLabel }}
                                </span>

                            </td>

                            {{-- STATUS --}}

                            <td>

                                <span
                                    class="tob-email-status tob-email-status-{{ $notification->status }}"
                                >
                                    {{ ucwords(str_replace('_', ' ', $notification->status)) }}
                                </span>

                                @if($isStuck)

                                    <small
                                        style="color:#a66a18;font-weight:700;"
                                    >
                                        ⚠ Investigation Required
                                    </small>

                                @elseif($notification->status === 'delivery_uncertain')

                                    <small
                                        style="color:#a34c18;font-weight:700;"
                                    >
                                        ⚠ Delivery Verification Required
                                    </small>

                                @endif

                            </td>

                            {{-- ATTEMPTS --}}

                            <td>

                                <span class="tob-email-attempts">
                                    {{ $notification->attempts }} / 5
                                </span>

                            </td>

                            {{-- CREATED DATE --}}

                            <td>

                                <span class="tob-email-date">
                                    {{ $notification->created_at?->format('d M Y') ?? '—' }}
                                </span>

                                <small>
                                    {{ $notification->created_at?->format('h:i A') ?? '' }}
                                </small>

                            </td>

                            {{-- SENT DATE --}}

                            <td>

                                @if($notification->sent_at)

                                    <span class="tob-email-date">
                                        {{ $notification->sent_at->format('d M Y') }}
                                    </span>

                                    <small>
                                        {{ $notification->sent_at->format('h:i A') }}
                                    </small>

                                @else

                                    <span class="tob-email-muted">
                                        —
                                    </span>

                                @endif

                            </td>

                            {{-- ACTIONS --}}

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.email-notifications.show',
                                        $notification
                                    ) }}"
                                    class="tob-email-investigate-link"
                                >
                                    View Details →
                                </a>

                                @if($notification->status === 'delivery_uncertain')

                                    <span class="tob-email-muted">
                                        Verify Delivery
                                    </span>

                                @elseif($isStuck)

                                    <span class="tob-email-muted">
                                        Investigate Stuck Email
                                    </span>

                                @elseif($notification->status === 'failed')

                                    <span class="tob-email-muted">
                                        Review & Retry
                                    </span>

                                @elseif($notification->status === 'sent')

                                    <span class="tob-email-muted">
                                        Completed
                                    </span>

                                @elseif($notification->status === 'processing')

                                    <span class="tob-email-muted">
                                        In Progress
                                    </span>

                                @else

                                    <span class="tob-email-muted">
                                        Queued
                                    </span>

                                @endif

                            </td>

                        </tr>

                        {{-- LAST ERROR DETAILS --}}

                        @if($notification->last_error)

                            <tr class="tob-email-error-row">

                                <td colspan="8">

                                    <div class="tob-email-error">

                                        <strong>
                                            Last Error:
                                        </strong>

                                        {{ $notification->last_error }}

                                    </div>

                                </td>

                            </tr>

                        @endif

                    @empty

                        {{-- EMPTY STATE --}}

                        <tr>

                            <td colspan="8">

                                <div class="tob-email-empty">

                                    <span>✉</span>

                                    <h3>
                                        No Notifications Found
                                    </h3>

                                    <p>
                                        @if(
                                            request()->filled('search')
                                            || request()->filled('status')
                                            || request()->filled('type')
                                        )

                                            No notifications match your current filters.
                                            Try changing or resetting the filters.

                                        @else

                                            Email notifications will appear here
                                            after customers place orders or
                                            order statuses are updated.

                                        @endif
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}

        @if($notifications->hasPages())

            <div class="tob-email-pagination">
                {{ $notifications->links() }}
            </div>

        @endif

    </div>

    {{-- =====================================================
         DELIVERY INFORMATION
    ====================================================== --}}

    <div class="tob-email-help">

        <strong>
            Delivery information
        </strong>

        <p>
            Sent means Laravel successfully handed the message
            to the configured mail transport. It does not
            guarantee inbox delivery.

            Failed notifications may be reviewed for manual
            retry after delivery verification.

            Delivery Uncertain notifications are excluded from
            automatic retry because the message may already
            have been accepted by the mail provider.

            Processing notifications are not automatically
            retried. Stuck notifications require worker
            verification and manual investigation.
        </p>

    </div>

</div>

@endsection
