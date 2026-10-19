
@extends('layouts.admin')

@section('title', $order->order_number . ' | The Only Baker Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-order-tracking.css') }}">
@endpush

@section('content')

@php
    $trackingSteps = [
        'pending' => 'Order Placed',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
    ];

    $statusPositions = array_flip(array_keys($trackingSteps));

    $currentPosition = $statusPositions[$order->order_status] ?? 0;

    $isCancelled = $order->order_status === 'cancelled';

    $nextStatuses = $allowedTransitions ?? [];

    $history = $order->statusHistory
        ->sortBy('created_at')
        ->values();

    $isCod = $order->payment_method === 'cod';

    $canMarkPaid = $isCod
        && $order->order_status === 'delivered'
        && $order->payment_status === 'pending';
@endphp

{{-- PAGE HEADING --}}
<div class="tob-admin-order-heading">
    <div>
        <a
            href="{{ route('admin.orders.index') }}"
            class="tob-admin-back"
        >
            ← Back to Orders
        </a>

        <span>ORDER DETAILS</span>

        <h1>{{ $order->order_number }}</h1>

        <p>
            Placed on
            {{ $order->created_at?->format('d M Y, h:i A') ?? '—' }}
        </p>
    </div>

    <span
        class="tob-status tob-status-large tob-status-{{ $order->order_status }}"
    >
        {{ ucfirst($order->order_status) }}
    </span>
</div>

{{-- FEEDBACK --}}
@if(session('success'))
    <div
        class="tob-admin-alert tob-admin-alert-success"
        role="status"
    >
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div
        class="tob-admin-alert tob-admin-alert-error"
        role="alert"
    >
        <strong>Please check the following:</strong>

        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="tob-admin-order-layout">

    {{-- MAIN COLUMN --}}
    <div class="tob-admin-order-main">

        {{-- ORDER TRACKING --}}
        <section class="tob-admin-card">
            <div class="tob-admin-card-heading">
                <h2>Order Tracking</h2>

                <span>
                    {{ $isCancelled
                        ? 'Cancelled'
                        : ucfirst($order->order_status) }}
                </span>
            </div>

            @if($isCancelled)
                <div class="tob-admin-cancelled-notice">
                    <strong>Order Cancelled</strong>

                    <p>
                        This order has been cancelled and
                        cannot be reactivated.
                    </p>
                </div>
            @else
                <div class="tob-admin-tracking">
                    @foreach($trackingSteps as $status => $label)
                        @php
                            $stepPosition = $statusPositions[$status];

                            $isComplete = $stepPosition <= $currentPosition;

                            $isCurrent = $stepPosition === $currentPosition;
                        @endphp

                        <div
                            class="tob-admin-tracking-step
                                {{ $isComplete ? 'is-complete' : '' }}
                                {{ $isCurrent ? 'is-current' : '' }}"
                        >
                            <div class="tob-admin-tracking-marker">
                                {{ $isComplete ? '✓' : $loop->iteration }}
                            </div>

                            <div class="tob-admin-tracking-copy">
                                <strong>{{ $label }}</strong>

                                <span>
                                    @if($status === 'pending')
                                        {{ $order->created_at?->format('d M Y, h:i A') ?? '—' }}
                                    @elseif($isCurrent)
                                        Current status
                                    @elseif($isComplete)
                                        Completed
                                    @else
                                        Awaiting update
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- STATUS HISTORY --}}
        <section class="tob-admin-card">
            <div class="tob-admin-card-heading">
                <h2>Status History</h2>

                <span>
                    {{ $history->count() }}
                    {{ \Illuminate\Support\Str::plural('Update', $history->count()) }}
                </span>
            </div>

            <div class="tob-admin-history">

                {{-- Original placement comes from order creation --}}
                <div class="tob-admin-history-entry">
                    <div class="tob-admin-history-dot"></div>

                    <div class="tob-admin-history-body">
                        <strong>Order Placed</strong>

                        <p>
                            Customer placed this order.
                        </p>

                        <small>
                            {{ $order->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </small>
                    </div>
                </div>

                @foreach($history as $entry)
                    <div class="tob-admin-history-entry">
                        <div class="tob-admin-history-dot"></div>

                        <div class="tob-admin-history-body">
                            <strong>
                                {{ ucfirst($entry->status) }}
                            </strong>

                            @if($entry->note)
                                <p>{{ $entry->note }}</p>
                            @endif

                            <small>
                                {{ $entry->created_at?->format('d M Y, h:i A') ?? '—' }}
                            </small>
                        </div>
                    </div>
                @endforeach

            </div>

            @if($history->isEmpty() && $order->order_status !== 'pending')
                <p class="tob-admin-history-note">
                    Earlier status changes may not have been
                    recorded before history tracking was enabled.
                </p>
            @endif
        </section>

        {{-- ORDER ITEMS --}}
        <section class="tob-admin-card">
            <div class="tob-admin-card-heading">
                <h2>Order Items</h2>

                <span>
                    {{ $order->items->sum('quantity') }}
                    {{ \Illuminate\Support\Str::plural(
                        'item',
                        $order->items->sum('quantity')
                    ) }}
                </span>
            </div>

            <div class="tob-admin-order-items">
                @forelse($order->items as $item)
                    <div class="tob-admin-order-item">

                        <div class="tob-admin-order-image">
                            @if($item->image)
                                <img
                                    src="{{ asset($item->image) }}"
                                    alt="{{ $item->product_name }}"
                                    loading="lazy"
                                >
                            @else
                                <span class="tob-admin-product-placeholder">
                                    TOB
                                </span>
                            @endif
                        </div>

                        <div class="tob-admin-order-product">
                            <strong>
                                {{ $item->product_name }}
                            </strong>

                            <span>
                                ₹{{ number_format((float) $item->price, 2) }}
                                × {{ $item->quantity }}
                            </span>

                            @if($item->product_slug)
                                <small>
                                    SKU: {{ $item->product_slug }}
                                </small>
                            @endif
                        </div>

                        <strong class="tob-admin-line-total">
                            ₹{{ number_format((float) $item->total, 2) }}
                        </strong>
                    </div>
                @empty
                    <p class="tob-admin-muted">
                        No order items found.
                    </p>
                @endforelse
            </div>
        </section>

        {{-- CUSTOMER INFORMATION --}}
        <section class="tob-admin-card">
            <div class="tob-admin-card-heading">
                <h2>Customer Information</h2>
            </div>

            <div class="tob-admin-info-grid">
                <div>
                    <span>Name</span>
                    <strong>{{ $order->name }}</strong>
                </div>

                <div>
                    <span>Phone</span>

                    <strong>
                        <a href="tel:{{ $order->phone }}">
                            {{ $order->phone }}
                        </a>
                    </strong>
                </div>

                <div>
                    <span>Email</span>

                    <strong>
                        <a href="mailto:{{ $order->email }}">
                            {{ $order->email }}
                        </a>
                    </strong>
                </div>

                <div>
                    <span>Payment Method</span>

                    <strong>
                        {{ $isCod
                            ? 'Cash on Delivery'
                            : strtoupper($order->payment_method ?? 'N/A') }}
                    </strong>
                </div>
            </div>
        </section>

        {{-- DELIVERY ADDRESS --}}
        <section class="tob-admin-card">
            <div class="tob-admin-card-heading">
                <h2>Delivery Address</h2>
            </div>

            <div class="tob-admin-address">
                <strong>{{ $order->name }}</strong>

                <p>
                    {{ $order->address }}

                    @if($order->landmark)
                        <br>
                        Landmark: {{ $order->landmark }}
                    @endif

                    <br>

                    {{ $order->city }},
                    {{ $order->state }}
                    - {{ $order->pincode }}

                    <br>

                    {{ $order->country }}
                </p>

                <span>{{ $order->phone }}</span>
            </div>
        </section>

    </div>

    {{-- SIDE COLUMN --}}
    <aside class="tob-admin-order-side">

        {{-- UPDATE STATUS --}}
        <section class="tob-admin-card">
            <div class="tob-admin-card-heading">
                <h2>Order Status</h2>
            </div>

            <div class="tob-admin-current-status">
                <span>Current Status</span>

                <span
                    class="tob-status tob-status-{{ $order->order_status }}"
                >
                    {{ ucfirst($order->order_status) }}
                </span>
            </div>

            @if(count($nextStatuses) > 0)
                <form
                    method="POST"
                    action="{{ route('admin.orders.status', $order) }}"
                    class="tob-admin-status-form"
                    onsubmit="return confirm(
                        this.elements['order_status'].value === 'cancelled'
                            ? 'Cancel this order? Previously deducted inventory will be restored if eligible. This action cannot be undone.'
                            : 'Are you sure you want to update this order status?'
                    )"
                >
                    @csrf
                    @method('PATCH')

                    <label for="orderStatus">
                        Change Status To
                    </label>

                    <select
                        name="order_status"
                        id="orderStatus"
                        required
                    >
                        <option value="">
                            Select Next Status
                        </option>

                        @foreach($nextStatuses as $status)
                            <option
                                value="{{ $status }}"
                                @selected(old('order_status') === $status)
                            >
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>

                    <label for="statusNote">
                        Status Note (Optional)
                    </label>

                    <textarea
                        name="note"
                        id="statusNote"
                        rows="3"
                        maxlength="255"
                        placeholder="Add a note for this status update..."
                        class="tob-admin-status-note"
                    >{{ old('note') }}</textarea>

                    <button type="submit">
                        Update Status
                    </button>

                    @if(in_array('cancelled', $nextStatuses, true))
                        <p class="tob-admin-muted">
                            Cancelling this order will restore
                            previously deducted inventory if it
                            has not already been restored.
                        </p>
                    @endif
                </form>
            @else
                <p class="tob-admin-muted">
                    @if($order->order_status === 'delivered')
                        This order has been delivered.
                        No further status changes are available.
                    @elseif($order->order_status === 'cancelled')
                        This order has been cancelled
                        and cannot be reactivated.
                    @else
                        No further status changes are available.
                    @endif
                </p>
            @endif
        </section>

        {{-- ORDER SUMMARY --}}
        <section class="tob-admin-card">
            <div class="tob-admin-card-heading">
                <h2>Order Summary</h2>
            </div>

            <div class="tob-admin-summary">
                <div>
                    <span>Subtotal</span>

                    <strong>
                        ₹{{ number_format((float) $order->subtotal, 2) }}
                    </strong>
                </div>

                <div>
                    <span>Shipping</span>

                    <strong>
                        @if(is_null($order->shipping))
                            Calculated separately
                        @elseif((float) $order->shipping === 0.0)
                            FREE
                        @else
                            ₹{{ number_format((float) $order->shipping, 2) }}
                        @endif
                    </strong>
                </div>

                <div class="total">
                    <span>
                        {{ is_null($order->shipping)
                            ? 'Order Subtotal'
                            : 'Total' }}
                    </span>

                    <strong>
                        ₹{{ number_format((float) $order->total, 2) }}
                    </strong>
                </div>
            </div>

            @if(is_null($order->shipping))
                <p class="tob-admin-muted">
                    Shipping charges, if applicable,
                    are not included in this amount.
                </p>
            @endif
        </section>

        {{-- PAYMENT --}}
        <section class="tob-admin-card">
            <div class="tob-admin-card-heading">
                <h2>Payment</h2>
            </div>

            <div class="tob-admin-payment-info">
                <span>Method</span>

                <strong>
                    {{ $isCod
                        ? 'Cash on Delivery'
                        : strtoupper($order->payment_method ?? 'N/A') }}
                </strong>

                <span>Payment Status</span>

                <strong>
                    {{ ucfirst($order->payment_status ?? 'pending') }}
                </strong>
            </div>

            @if($canMarkPaid)
                <form
                    method="POST"
                    action="{{ route('admin.orders.payment', $order) }}"
                    class="tob-admin-payment-form"
                    onsubmit="return confirm('Confirm that COD payment has been received?')"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="tob-admin-payment-button"
                    >
                        Mark as Paid
                    </button>
                </form>
            @elseif($order->payment_status === 'paid')
                <p class="tob-admin-payment-success">
                    Payment received successfully.
                </p>
            @elseif($isCod && !$isCancelled)
                <p class="tob-admin-muted">
                    COD payment can be confirmed after delivery.
                </p>
            @endif
        </section>

        {{-- INVENTORY --}}
        <section class="tob-admin-card">
            <div class="tob-admin-card-heading">
                <h2>Inventory Status</h2>
            </div>

            <div class="tob-admin-inventory-details">
                <div>
                    <span>Stock Deducted</span>

                    <strong>
                        {{ $order->inventory_deducted_at
                            ? $order->inventory_deducted_at->format('d M Y, h:i A')
                            : 'Not recorded' }}
                    </strong>
                </div>

                <div>
                    <span>Stock Restored</span>

                    <strong>
                        {{ $order->inventory_restored_at
                            ? $order->inventory_restored_at->format('d M Y, h:i A')
                            : 'Not recorded' }}
                    </strong>
                </div>
            </div>
        </section>

    </aside>
</div>

@endsection
