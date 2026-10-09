@extends('layouts.admin')

@section('title', 'Orders | The Only Baker Admin')


@section('content')

<div class="tob-admin-page-heading">

    <div>

        <span>STORE MANAGEMENT</span>

        <h1>Orders</h1>

        <p>
            Manage and track customer orders.
        </p>

    </div>

</div>


{{-- STATS --}}
<div class="tob-admin-stats">

    <div class="tob-admin-stat">

        <span>Total Orders</span>

        <strong>
            {{ $stats['total'] }}
        </strong>

    </div>


    <div class="tob-admin-stat">

        <span>Pending</span>

        <strong>
            {{ $stats['pending'] }}
        </strong>

    </div>


    <div class="tob-admin-stat">

        <span>Confirmed</span>

        <strong>
            {{ $stats['confirmed'] }}
        </strong>

    </div>


    <div class="tob-admin-stat">

        <span>Shipped</span>

        <strong>
            {{ $stats['shipped'] }}
        </strong>

    </div>


    <div class="tob-admin-stat">

        <span>Delivered</span>

        <strong>
            {{ $stats['delivered'] }}
        </strong>

    </div>

</div>


{{-- FILTERS --}}
<div class="tob-admin-card tob-admin-filter-card">

    <form
        method="GET"
        action="{{ route('admin.orders.index') }}"
        class="tob-admin-filters"
    >

        <div class="tob-admin-search">

            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search order, customer, email or phone..."
            >

        </div>


        <select name="status">

            <option value="">
                All Statuses
            </option>

            <option
                value="pending"
                @selected(request('status') === 'pending')
            >
                Pending
            </option>

            <option
                value="confirmed"
                @selected(request('status') === 'confirmed')
            >
                Confirmed
            </option>

            <option
                value="shipped"
                @selected(request('status') === 'shipped')
            >
                Shipped
            </option>

            <option
                value="delivered"
                @selected(request('status') === 'delivered')
            >
                Delivered
            </option>

            <option
                value="cancelled"
                @selected(request('status') === 'cancelled')
            >
                Cancelled
            </option>

        </select>


        <button type="submit">
            Filter
        </button>


        @if(request('search') || request('status'))

            <a
                href="{{ route('admin.orders.index') }}"
                class="tob-admin-reset"
            >
                Reset
            </a>

        @endif

    </form>

</div>


{{-- ORDERS --}}
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
                    <th></th>

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

                        </td>


                        <td>
                            {{ $order->items_count }}
                        </td>


                        <td class="tob-admin-price">

                            ₹{{ number_format($order->total, 0) }}

                        </td>


                        <td>

                            <span class="tob-payment-badge">
                                COD
                            </span>

                        </td>


                        <td>

                            <span
                                class="
                                    tob-status
                                    tob-status-{{ $order->order_status }}
                                "
                            >
                                {{ ucfirst($order->order_status) }}
                            </span>

                        </td>


                        <td>

                            <span class="tob-order-date">
                                {{ $order->created_at->format('d M Y') }}
                            </span>

                            <small>
                                {{ $order->created_at->format('h:i A') }}
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

                        <td
                            colspan="8"
                            class="tob-admin-empty"
                        >

                            <strong>
                                No orders found
                            </strong>

                            <span>
                                New customer orders will appear here.
                            </span>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


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