
@php
    $steps = [
        'pending' => 'Order Placed',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
    ];

    $statuses = array_keys($steps);

    $current = array_search(
        $order->order_status,
        $statuses,
        true
    );

    $cancelled = $order->order_status === 'cancelled';

    $history = $order->statusHistory->sortBy('created_at');
@endphp

<section
    class="tob-order-detail-card"
    aria-label="Order tracking"
>
    <div class="tob-order-detail-card-heading">
        <div>
            <span>ORDER STATUS</span>
            <h2>Track Your Order</h2>
        </div>
    </div>

    @if($cancelled)

        <div
            class="tob-order-tracking-cancelled"
            role="status"
        >
            This order has been cancelled.
        </div>

    @else

        <ol class="tob-order-tracking-list">

            @foreach($steps as $status => $label)

                @php
                    $position = array_search(
                        $status,
                        $statuses,
                        true
                    );

                    $done = $current !== false
                        && $position <= $current;

                    $event = $history
                        ->where('status', $status)
                        ->first();

                    $date = $status === 'pending'
                        ? $order->created_at
                        : $event?->created_at;
                @endphp

                <li
                    class="tob-order-tracking-step {{ $done ? 'is-complete' : '' }}"
                    @if($position === $current)
                        aria-current="step"
                    @endif
                >
                    <span class="tob-order-tracking-marker">
                        {{ $done ? '✓' : $loop->iteration }}
                    </span>

                    <div>
                        <strong>{{ $label }}</strong>

                        @if($done && $date)

                            <small>
                                {{ $date->format('d M Y, h:i A') }}
                            </small>

                        @elseif($done)

                            <small>
                                Completed — exact date unavailable
                            </small>

                        @else

                            <small>Upcoming</small>

                        @endif

                        @if($event?->note)
                            <p>{{ $event->note }}</p>
                        @endif
                    </div>
                </li>

            @endforeach

        </ol>

    @endif
</section>
