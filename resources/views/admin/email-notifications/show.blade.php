
@extends('layouts.admin')

@section('title', 'Email Investigation | The Only Baker')

@push('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/admin-email-notifications.css') }}"
>
@endpush

@section('content')

@php
    $isStuck = $notification->isStuck();

    $canRecover = (
        in_array($notification->status, [
            'failed',
            'delivery_uncertain',
        ], true)
        || $isStuck
    ) && $notification->sent_at === null;

    $recoveryRoute = match (true) {
        $notification->status === 'delivery_uncertain'
            => 'admin.email-notifications.recover-uncertain',

        $isStuck
            => 'admin.email-notifications.recover',

        default
            => 'admin.email-notifications.retry',
    };

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

<div class="tob-email-admin">

    {{-- =====================================================
         PAGE HEADING
    ====================================================== --}}

    <div class="tob-email-heading">

        <div>

            <span class="tob-email-eyebrow">
                DELIVERY INVESTIGATION
            </span>

            <h1>
                Email Notification #{{ $notification->id }}
            </h1>

            <p>
                Review delivery status, diagnostics and recovery history.
            </p>

        </div>

        <a
            href="{{ route('admin.email-notifications.index') }}"
            class="tob-email-refresh"
        >
            ← Back to Notifications
        </a>

    </div>

    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div
            class="tob-email-investigation-card"
            role="status"
        >
            <strong>
                {{ session('success') }}
            </strong>
        </div>

    @endif

    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div
            class="tob-email-investigation-card"
            style="border-color:#e9aaa5;color:#a73d37;"
            role="alert"
        >

            <strong>
                Please review the following errors:
            </strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

    {{-- =====================================================
         NOTIFICATION DETAILS
    ====================================================== --}}

    <div class="tob-email-investigation-card">

        <h2>Notification Details</h2>

        <div class="tob-email-detail-grid">

            {{-- ORDER NUMBER --}}

            <div>

                <span>Order Number</span>

                <strong>

                    @if($notification->order)

                        <a
                            href="{{ route(
                                'admin.orders.show',
                                $notification->order
                            ) }}"
                        >
                            {{ $notification->order->order_number }}
                        </a>

                    @else

                        Unavailable

                    @endif

                </strong>

            </div>

            {{-- RECIPIENT --}}

            <div>

                <span>Recipient</span>

                <strong>
                    {{ $notification->recipient_email }}
                </strong>

            </div>

            {{-- EMAIL TYPE --}}

            <div>

                <span>Email Type</span>

                <strong>
                    {{ $typeLabel }}
                </strong>

            </div>

            {{-- STATUS --}}

            <div>

                <span>Status</span>

                <strong>

                    <span
                        class="tob-email-status tob-email-status-{{ $notification->status }}"
                    >
                        {{ ucwords(str_replace('_', ' ', $notification->status)) }}
                    </span>

                </strong>

            </div>

            {{-- ATTEMPTS --}}

            <div>

                <span>Attempts</span>

                <strong>
                    {{ $notification->attempts }} / 5
                </strong>

            </div>

            {{-- CREATED --}}

            <div>

                <span>Created</span>

                <strong>
                    {{ $notification->created_at?->format('d M Y, h:i A') ?? '—' }}
                </strong>

            </div>

            {{-- PROCESSING STARTED --}}

            <div>

                <span>Processing Started</span>

                <strong>
                    {{ $notification->processing_started_at?->format('d M Y, h:i A') ?? '—' }}
                </strong>

            </div>

            {{-- SEND STARTED --}}

            <div>

                <span>Send Started</span>

                <strong>
                    {{ $notification->send_started_at?->format('d M Y, h:i A') ?? '—' }}
                </strong>

            </div>

            {{-- SENT AT --}}

            <div>

                <span>Sent At</span>

                <strong>
                    {{ $notification->sent_at?->format('d M Y, h:i A') ?? '—' }}
                </strong>

            </div>

            {{-- NEXT RETRY --}}

            <div>

                <span>Next Retry</span>

                <strong>
                    {{ $notification->next_attempt_at?->format('d M Y, h:i A') ?? '—' }}
                </strong>

            </div>

            {{-- PROCESSING TOKEN --}}

            <div>

                <span>Processing Token</span>

                <strong style="overflow-wrap:anywhere;">
                    {{ $notification->processing_token ?? '—' }}
                </strong>

            </div>

            {{-- LAST UPDATED --}}

            <div>

                <span>Last Updated</span>

                <strong>
                    {{ $notification->updated_at?->format('d M Y, h:i A') ?? '—' }}
                </strong>

            </div>

        </div>

        {{-- LAST ERROR --}}

        @if($notification->last_error)

            <div class="tob-email-investigation-error">

                <strong>
                    Last Error
                </strong>

                <pre>{{ $notification->last_error }}</pre>

            </div>

        @endif

    </div>

    {{-- =====================================================
         NOTIFICATION PURPOSE
    ====================================================== --}}

    <div class="tob-email-investigation-card">

        <h2>Notification Purpose</h2>

        <p class="tob-email-investigation-description">

            @switch($notification->type)

                @case('customer')

                    Customer notification generated when a new order
                    is successfully placed.
                    @break

                @case('admin')

                    Admin notification generated when a customer
                    places a new order.
                    @break

                @case('order_confirmed')

                    Customer notification generated when the
                    admin confirms an order.
                    @break

                @case('order_shipped')

                    Customer notification generated when the
                    order is marked as shipped.
                    @break

                @case('order_delivered')

                    Customer notification generated when the
                    order is marked as delivered.
                    @break

                @case('order_cancelled')

                    Customer notification generated when the
                    order is cancelled.
                    @break

                @case('payment_received')

                    Customer notification generated when a
                    Cash on Delivery payment is marked as paid.
                    @break

                @default

                    Notification purpose unavailable.

            @endswitch

        </p>

    </div>

    {{-- =====================================================
         DELIVERY DIAGNOSTICS
    ====================================================== --}}

    @if($notification->status === 'delivery_uncertain')

        <div class="tob-email-investigation-warning">

            <strong>
                Delivery outcome uncertain
            </strong>

            <p>
                An error occurred after the email send attempt
                started. The recipient may already have received
                this email. Automatic retry is disabled.

                Verify the delivery outcome with the configured
                mail provider before initiating recovery.
            </p>

        </div>

    @elseif($isStuck)

        <div class="tob-email-investigation-warning">

            <strong>
                Potentially stuck notification
            </strong>

            <p>
                This notification has remained in Processing
                for more than five minutes. Confirm that the
                original worker is no longer executing the
                delivery attempt, then verify non-delivery
                before attempting recovery.
            </p>

        </div>

    @elseif($notification->status === 'sent')

        <div class="tob-email-investigation-card">

            <h2>Delivery Status</h2>

            <p class="tob-email-investigation-description">
                The configured mail transport accepted this
                email. Inbox delivery is not guaranteed.

                This notification cannot be manually retried.
            </p>

        </div>

    @elseif($notification->status === 'processing')

        <div class="tob-email-investigation-card">

            <h2>Delivery Status</h2>

            <p class="tob-email-investigation-description">
                This notification is currently processing.

                Manual recovery is unavailable until it meets
                the investigation threshold.
            </p>

        </div>

    @elseif($notification->status === 'pending')

        <div class="tob-email-investigation-card">

            <h2>Delivery Status</h2>

            <p class="tob-email-investigation-description">
                This notification is pending processing.
                Check the email queue worker and dispatch
                configuration if it remains pending.
            </p>

        </div>

    @endif

    {{-- =====================================================
         MANUAL RECOVERY
    ====================================================== --}}

    @if($canRecover)

        <div class="tob-email-investigation-card">

            <h2>Manual Recovery</h2>

            <p class="tob-email-investigation-description">
                Recovery may result in a duplicate email if
                a previous attempt reached the recipient.

                Only proceed after confirming non-delivery
                and ensuring that the original worker is
                no longer active.
            </p>

            <form
                method="POST"
                action="{{ route($recoveryRoute, $notification) }}"
                onsubmit="return confirm('Have you independently verified non-delivery and confirmed the original worker is no longer active?');"
            >

                @csrf

                {{-- DELIVERY VERIFICATION --}}

                <div class="tob-email-investigation-field">

                    <label for="deliveryVerification">
                        Delivery Verification
                    </label>

                    <select
                        id="deliveryVerification"
                        name="delivery_verification"
                        required
                    >

                        <option value="">
                            Select verification result
                        </option>

                        <option
                            value="delivery_uncertain"
                            @selected(old('delivery_verification') === 'delivery_uncertain')
                        >
                            Delivery uncertain — do not resend
                        </option>

                        <option
                            value="confirmed_not_delivered"
                            @selected(old('delivery_verification') === 'confirmed_not_delivered')
                        >
                            Confirmed not delivered
                        </option>

                    </select>

                    <small>
                        The server permits recovery only when
                        "Confirmed not delivered" is selected.
                    </small>

                </div>

                {{-- INVESTIGATION NOTES --}}

                <div class="tob-email-investigation-field">

                    <label for="recoveryReason">
                        Investigation Notes
                    </label>

                    <textarea
                        id="recoveryReason"
                        name="reason"
                        rows="5"
                        minlength="10"
                        maxlength="2000"
                        required
                        placeholder="Explain how non-delivery was verified, including relevant provider evidence."
                    >{{ old('reason') }}</textarea>

                </div>

                {{-- WORKER CONFIRMATION --}}

                <div class="tob-email-investigation-field">

                    <label class="tob-email-worker-confirmation">

                        <input
                            type="checkbox"
                            name="worker_stopped"
                            value="1"
                            @checked(old('worker_stopped'))
                            required
                        >

                        <span>
                            I have verified that the original
                            email worker is no longer executing
                            this delivery attempt.
                        </span>

                    </label>

                </div>

                <button
                    type="submit"
                    class="tob-email-retry"
                >
                    Confirm & Queue Recovery
                </button>

            </form>

        </div>

    @endif

    {{-- =====================================================
         RECOVERY AUDIT HISTORY
    ====================================================== --}}

    <div class="tob-email-investigation-card">

        <h2>Recovery Audit History</h2>

        @forelse($notification->recoveryLogs as $log)

            <div class="tob-email-audit-entry">

                <div>

                    <strong>
                        {{ ucwords(str_replace('_', ' ', $log->action)) }}
                    </strong>

                    <span>
                        {{ $log->created_at?->format('d M Y, h:i A') ?? '—' }}
                    </span>

                </div>

                <p>
                    {{ ucwords(str_replace('_', ' ', $log->previous_status)) }}
                    →
                    {{ ucwords(str_replace('_', ' ', $log->new_status)) }}
                </p>

                <p>
                    <strong>Admin ID:</strong>
                    {{ $log->admin_id }}
                </p>

                <p>
                    <strong>Verification:</strong>
                    {{ ucwords(str_replace('_', ' ', $log->delivery_verification)) }}
                </p>

                <p>
                    <strong>Reason:</strong>
                    {{ $log->reason }}
                </p>

            </div>

        @empty

            <p class="tob-email-investigation-description">
                No manual recovery actions recorded.
            </p>

        @endforelse

    </div>

    {{-- =====================================================
         DELIVERY INFORMATION
    ====================================================== --}}

    <div class="tob-email-help">

        <strong>
            Delivery information
        </strong>

        <p>
            Sent means the configured mail transport accepted
            the message.

            Delivery Uncertain means the outcome of the send
            attempt is unknown.

            Processing tokens protect database status updates,
            but cannot cancel an email already being sent.

            All recovery actions must be verified before
            submission.
        </p>

    </div>

</div>

@endsection
