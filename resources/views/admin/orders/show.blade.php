@extends('layouts.admin')

@section('title', $order->order_number . ' | Admin')


@section('content')

<div class="tob-admin-order-heading">

    <div>

        <a
            href="{{ route('admin.orders.index') }}"
            class="tob-admin-back"
        >
            ← Back to Orders
        </a>

        <span>
            ORDER DETAILS
        </span>

        <h1>
            {{ $order->order_number }}
        </h1>

        <p>
            Placed on
            {{ $order->created_at->format('d M Y, h:i A') }}
        </p>

    </div>


    <span
        class="
            tob-status
            tob-status-large
            tob-status-{{ $order->order_status }}
        "
    >
        {{ ucfirst($order->order_status) }}
    </span>

</div>


<div class="tob-admin-order-layout">

    {{-- LEFT --}}
    <div class="tob-admin-order-main">

        {{-- PRODUCTS --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Order Items
                </h2>

                <span>
                    {{ $order->items->sum('quantity') }}
                    items
                </span>

            </div>


            <div class="tob-admin-order-items">

                @foreach($order->items as $item)

                    <div class="tob-admin-order-item">

                        <div class="tob-admin-order-image">

                            @if($item->image)

                                <img
                                    src="{{ asset($item->image) }}"
                                    alt="{{ $item->product_name }}"
                                >

                            @endif

                        </div>


                        <div class="tob-admin-order-product">

                            <strong>
                                {{ $item->product_name }}
                            </strong>

                            <span>
                                ₹{{ number_format($item->price, 0) }}
                                ×
                                {{ $item->quantity }}
                            </span>

                        </div>


                        <strong class="tob-admin-line-total">
                            ₹{{ number_format($item->total, 0) }}
                        </strong>

                    </div>

                @endforeach

            </div>

        </section>


        {{-- CUSTOMER --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Customer Information
                </h2>

            </div>


            <div class="tob-admin-info-grid">

                <div>

                    <span>
                        Name
                    </span>

                    <strong>
                        {{ $order->name }}
                    </strong>

                </div>


                <div>

                    <span>
                        Phone
                    </span>

                    <strong>
                        {{ $order->phone }}
                    </strong>

                </div>


                <div>

                    <span>
                        Email
                    </span>

                    <strong>
                        {{ $order->email }}
                    </strong>

                </div>


                <div>

                    <span>
                        Payment
                    </span>

                    <strong>
                        Cash on Delivery
                    </strong>

                </div>

            </div>

        </section>


        {{-- ADDRESS --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Delivery Address
                </h2>

            </div>


            <div class="tob-admin-address">

                <strong>
                    {{ $order->name }}
                </strong>

                <p>

                    {{ $order->address }}

                    @if($order->landmark)

                        <br>

                        Landmark:
                        {{ $order->landmark }}

                    @endif

                    <br>

                    {{ $order->city }},
                    {{ $order->state }}
                    - {{ $order->pincode }}

                    <br>

                    {{ $order->country }}

                </p>

                <span>
                    {{ $order->phone }}
                </span>

            </div>

        </section>

    </div>


    {{-- RIGHT --}}
    <aside class="tob-admin-order-side">

        {{-- STATUS --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Order Status
                </h2>

            </div>


            <form
                method="POST"
                action="{{ route('admin.orders.status', $order) }}"
                class="tob-admin-status-form"
            >

                @csrf
                @method('PATCH')


                <label for="orderStatus">
                    Current Status
                </label>


                <select
                    name="order_status"
                    id="orderStatus"
                >

                    <option
                        value="pending"
                        @selected($order->order_status === 'pending')
                    >
                        Pending
                    </option>

                    <option
                        value="confirmed"
                        @selected($order->order_status === 'confirmed')
                    >
                        Confirmed
                    </option>

                    <option
                        value="shipped"
                        @selected($order->order_status === 'shipped')
                    >
                        Shipped
                    </option>

                    <option
                        value="delivered"
                        @selected($order->order_status === 'delivered')
                    >
                        Delivered
                    </option>

                    <option
                        value="cancelled"
                        @selected($order->order_status === 'cancelled')
                    >
                        Cancelled
                    </option>

                </select>


                <button type="submit">
                    Update Status
                </button>

            </form>

        </section>


        {{-- SUMMARY --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Order Summary
                </h2>

            </div>


            <div class="tob-admin-summary">

                <div>

                    <span>
                        Subtotal
                    </span>

                    <strong>
                        ₹{{ number_format($order->subtotal, 0) }}
                    </strong>

                </div>


                <div>

                    <span>
                        Shipping
                    </span>

                    <strong>

                        @if(is_null($order->shipping))

                            Calculated separately

                        @elseif((float) $order->shipping === 0.0)

                            FREE

                        @else

                            ₹{{ number_format($order->shipping, 0) }}

                        @endif

                    </strong>

                </div>


                <div class="total">

                    <span>
                        {{ is_null($order->shipping) ? 'Order Subtotal' : 'Total' }}
                    </span>

                    <strong>
                        ₹{{ number_format($order->total, 0) }}
                    </strong>

                </div>

            </div>


            @if(is_null($order->shipping))

                <p
                    style="
                        margin:14px 0 0;
                        color:#806d61;
                        font-size:11px;
                        line-height:1.6;
                    "
                >
                    Shipping charges, if applicable,
                    have not been included in this amount.
                </p>

            @endif

        </section>


        {{-- PAYMENT --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Payment
                </h2>

            </div>


            <div class="tob-admin-payment-info">

                <span>
                    Method
                </span>

                <strong>
                    Cash on Delivery
                </strong>


                <span>
                    Payment Status
                </span>

                <strong>
                    {{ ucfirst($order->payment_status) }}
                </strong>

            </div>

        </section>

    </aside>

</div>

@endsection