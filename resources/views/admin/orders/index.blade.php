
@extends('layouts.admin')

@section('title', 'Orders | The Only Baker Admin')

@section('content')

@php
    $statusOptions = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];

    $statCards = [
        ['label' => 'Total Orders', 'value' => $stats['total'] ?? 0],
        ['label' => 'Pending', 'value' => $stats['pending'] ?? 0],
        ['label' => 'Confirmed', 'value' => $stats['confirmed'] ?? 0],
        ['label' => 'Processing', 'value' => $stats['processing'] ?? 0],
        ['label' => 'Shipped', 'value' => $stats['shipped'] ?? 0],
        ['label' => 'Delivered', 'value' => $stats['delivered'] ?? 0],
        ['label' => 'Cancelled', 'value' => $stats['cancelled'] ?? 0],
    ];
@endphp

<div class="tob-admin-page-heading">
    <div>
        <span>STORE MANAGEMENT</span>
        <h1>Orders</h1>
        <p>Manage, track and fulfil customer orders.</p>
    </div>
</div>

{{-- ORDER STATISTICS --}}
<div class="tob-admin-stats">
    @foreach($statCards as $stat)
        <div class="tob-admin-stat">
            <span>{{ $stat['label'] }}</span>
            <strong>{{ number_format($stat['value']) }}</strong>
        </div>
    @endforeach
</div>

{{-- SEARCH AND FILTER --}}
<div class="tob-admin-card tob-admin-filter-card">
    <form
        method="GET"
        action="{{ route('admin.orders.index') }}"
        class="tob-admin-filters"
        role="search"
    >
        <div class="tob-admin-search">
            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search order, customer, email or phone..."
                aria-label="Search orders"
            >
        </div>

        <select name="status" aria-label="Filter by status">
            <option value="">All Statuses</option>

            @foreach($statusOptions as $value => $label)
                <option
                    value="{{ $value }}"
                    @selected(request('status') === $value)
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <button type="submit">
            Filter
        </button>

        @if(request()->filled('search') || request()->filled('status'))
            <a
                href="{{ route('admin.orders.index') }}"
                class="tob-admin-reset"
            >
                Reset
            </a>
        @endif
    </form>
</div>

{{-- ORDER TABLE --}}
<div class="tob-admin-card">
    <div class="tob-admin-table-wrap">
        <table class="tob-admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <a
                                href="{{ route('admin.orders.show', $order) }}"
                                class="tob-order-number"
                            >
                                {{ $order->order_number }}
                            </a>
                        </td>

                        <td>
                            <strong class="tob-customer-name">
                                {{ $order->name }}
                            </strong>

                            <small>
                                {{ $order->phone }}
                            </small>

                            <small>
                                {{ $order->email }}
                            </small>
                        </td>

                        <td>
                            {{ $order->items_count }}
                        </td>

                        <td class="tob-admin-price">
                            ₹{{ number_format((float) $order->total, 2) }}
                        </td>

                        <td>
                            <span class="tob-payment-badge">
                                {{ $order->payment_method === 'cod'
                                    ? 'COD'
                                    : strtoupper($order->payment_method ?? 'N/A') }}
                            </span>

                            <small class="tob-order-payment-status">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </small>
                        </td>

                        <td>
                            <span
                                class="tob-status tob-status-{{ $order->order_status }}"
                            >
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>

                        <td>
                            <span class="tob-order-date">
                                {{ $order->created_at?->format('d M Y') ?? '—' }}
                            </span>

                            <small>
                                {{ $order->created_at?->format('h:i A') ?? '' }}
                            </small>
                        </td>

                        <td>
                            <a
                                href="{{ route('admin.orders.show', $order) }}"
                                class="tob-view-order"
                            >
                                View →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="tob-admin-empty">
                            <strong>No orders found</strong>

                            <span>
                                @if(request()->filled('search') || request()->filled('status'))
                                    No orders match your selected filters.
                                @else
                                    New customer orders will appear here.
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if($orders->hasPages())
        <div class="tob-admin-pagination">
            @if($orders->onFirstPage())
                <span class="disabled">
                    ← Previous
                </span>
            @else
                <a href="{{ $orders->previousPageUrl() }}">
                    ← Previous
                </a>
            @endif

            <span>
                Page {{ $orders->currentPage() }}
                of {{ $orders->lastPage() }}
            </span>

            @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}">
                    Next →
                </a>
            @else
                <span class="disabled">
                    Next →
                </span>
            @endif
        </div>
    @endif
</div>

@endsection
