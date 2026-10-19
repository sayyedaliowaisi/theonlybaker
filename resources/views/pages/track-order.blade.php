
@extends('layouts.app')

@section('title', 'Track Your Order | The Only Baker')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/track-order.css') }}">
@endpush

@section('content')

@php
    $trackedOrder = $trackedOrder ?? null;
    $trackingError = $trackingError ?? null;

    $stages = [
        'pending' => 'Order Received',
        'confirmed' => 'Confirmed',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
    ];

    $statusLabels = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];

    $currentStatus = $trackedOrder?->order_status;

    $statusIndex = array_search(
        $currentStatus,
        array_keys($stages),
        true
    );

    $paymentLabels = [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
    ];
@endphp

<main class="tob-tracking">

    <section class="tob-tracking-hero">
        <div class="tob-tracking-container">

            <span class="tob-tracking-eyebrow">
                THE ONLY BAKER
            </span>

            <h1>Track Your Order</h1>

            <p>
                Your favourite treats are on their way.
                Check your order's latest status here.
            </p>

        </div>
    </section>

    <section class="tob-tracking-section">

        <div class="tob-tracking-container">

            <div class="tob-tracking-panel">

                <div class="tob-tracking-intro">
                    <h2>Where's My Order?</h2>

                    <p>
    Enter your order number to check the latest
    status of an order placed from your account.
</p>
                </div>

                @if ($errors->any())
    <div class="tob-tracking-alert" role="alert">
        Please enter a valid order number.
    </div>
@endif

                @if ($trackingError)
                    <div class="tob-tracking-alert" role="alert">
                        {{ $trackingError }}
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('orders.track.search') }}"
                    class="tob-tracking-form"
                >
                    @csrf

                    <div class="tob-tracking-field">
                        <label for="order_number">
                            Order Number
                        </label>

                        <input
                            id="order_number"
                            type="text"
                            name="order_number"
                            value="{{ old('order_number') }}"
                            placeholder="Enter your order number"
                            maxlength="100"
                            autocomplete="off"
                            required
                        >

                        @error('order_number')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                   

                    <button type="submit" class="tob-tracking-submit">
                        Track My Order
                        <span aria-hidden="true">→</span>
                    </button>
                </form>

            </div>

            @if ($trackedOrder)

                <section class="tob-tracking-result" aria-live="polite">

                    <div class="tob-tracking-result-header">
                        <div>
                            <span class="tob-tracking-eyebrow">
                                ORDER DETAILS
                            </span>

                            <h2>
                                Order #{{ $trackedOrder->order_number }}
                            </h2>
                        </div>

                        <span class="tob-tracking-status
                            {{ $currentStatus === 'cancelled' ? 'is-cancelled' : '' }}">
                            {{ $statusLabels[$currentStatus] ?? ucfirst((string) $currentStatus) }}
                        </span>
                    </div>

                    <div class="tob-tracking-details">

                        <div>
                            <span>Order Placed</span>
                            <strong>
                                {{ $trackedOrder->created_at?->format('d M Y') ?? '—' }}
                            </strong>
                        </div>

                        <div>
                            <span>Payment Method</span>
                            <strong>
                                {{ strtoupper((string) $trackedOrder->payment_method) }}
                            </strong>
                        </div>

                        <div>
                            <span>Payment Status</span>
                            <strong>
                                {{ $paymentLabels[$trackedOrder->payment_status] ?? ucfirst((string) $trackedOrder->payment_status) }}
                            </strong>
                        </div>

                        <div>
                            <span>Order Total</span>
                            <strong>
                                ₹{{ number_format((float) $trackedOrder->total, 2) }}
                            </strong>
                        </div>

                    </div>

                    @if ($currentStatus === 'cancelled')

                        <div class="tob-tracking-cancelled">
                            <h3>Order Cancelled</h3>

                            <p>
                                This order has been cancelled.
                                Please contact our team if you
                                need assistance.
                            </p>
                        </div>

                    @elseif (array_key_exists($currentStatus, $stages))

                        <div class="tob-tracking-progress">

                            <h3>Order Progress</h3>

                            <p class="tob-tracking-progress-note">
                                Current status from our order system
                            </p>

                            <ol class="tob-tracking-timeline">

                                @foreach ($stages as $key => $label)

                                    @php
                                        $stepIndex = array_search(
                                            $key,
                                            array_keys($stages),
                                            true
                                        );

                                        $isComplete = $stepIndex < $statusIndex;
                                        $isCurrent = $stepIndex === $statusIndex;
                                    @endphp

                                    <li class="tob-tracking-step
                                        {{ $isComplete ? 'is-complete' : '' }}
                                        {{ $isCurrent ? 'is-current' : '' }}">

                                        <span class="tob-tracking-step-dot">
                                            {{ $isComplete ? '✓' : $loop->iteration }}
                                        </span>

                                        <div>
                                            <strong>{{ $label }}</strong>

                                            @if ($isCurrent)
                                                <span>Current status</span>
                                            @elseif ($isComplete)
                                                <span>Completed stage</span>
                                            @else
                                                <span>Upcoming stage</span>
                                            @endif
                                        </div>

                                    </li>

                                @endforeach

                            </ol>

                            <p class="tob-tracking-progress-note">
                                This timeline shows order stages, not
                                individual event timestamps or courier GPS tracking.
                            </p>

                        </div>

                    @else

                        <p class="tob-tracking-progress-note">
                            Please contact support for an update
                            on this order.
                        </p>

                    @endif

                    <div class="tob-tracking-help">
                        <p>Need help with your order?</p>

                        <a href="{{ route('contact') }}">
                            Contact Support →
                        </a>
                    </div>

                </section>

            @else

                <div class="tob-tracking-help">
                    <p>
                        Can't find your order confirmation email?
                    </p>

                    <a href="{{ route('contact') }}">
                        Contact Our Team →
                    </a>
                </div>

            @endif

        </div>

    </section>

</main>

@endsection
