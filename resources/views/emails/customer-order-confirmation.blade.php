
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Order Received - The Only Baker</title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#f7f1eb;
    font-family:Arial,Helvetica,sans-serif;
    color:#342117;
">

<table
    role="presentation"
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="background:#f7f1eb;"
>
    <tr>
        <td align="center" style="padding:30px 15px;">

            <table
                role="presentation"
                width="100%"
                cellpadding="0"
                cellspacing="0"
                border="0"
                style="
                    width:100%;
                    max-width:620px;
                    background:#ffffff;
                    border:1px solid #e8ddd2;
                "
            >

                {{-- HEADER --}}
                <tr>
                    <td
                        align="center"
                        style="
                            padding:30px 25px;
                            background:#4b2415;
                        "
                    >

                        <div style="
                            color:#d8bba7;
                            font-size:10px;
                            font-weight:bold;
                            letter-spacing:2px;
                        ">
                            THE ONLY BAKER
                        </div>

                        <h1 style="
                            margin:8px 0 0;
                            color:#ffffff;
                            font-family:Georgia,serif;
                            font-size:28px;
                            font-weight:normal;
                        ">
                            Order Received
                        </h1>

                        <p style="
                            margin:12px 0 0;
                            color:#e7d1c2;
                            font-size:12px;
                            line-height:1.6;
                        ">
                            Thank you for placing your order!
                        </p>

                    </td>
                </tr>

                {{-- CUSTOMER MESSAGE --}}
                <tr>
                    <td style="padding:32px 30px 20px;">

                        <p style="
                            margin:0 0 12px;
                            font-size:15px;
                            font-weight:bold;
                        ">
                            Hi {{ $order->name }},
                        </p>

                        <p style="
                            margin:0;
                            color:#766359;
                            font-size:13px;
                            line-height:1.8;
                        ">
                            Thank you for ordering from
                            <strong>The Only Baker</strong>.

                            We have successfully received your
                            order request.

                            Our team will review your order
                            and prepare your freshly baked
                            treats with care once it is confirmed.
                        </p>

                    </td>
                </tr>

                {{-- ORDER NUMBER --}}
                <tr>
                    <td style="padding:0 30px 25px;">

                        <table
                            role="presentation"
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            style="background:#f7f0e9;"
                        >
                            <tr>
                                <td
                                    align="center"
                                    style="padding:18px;"
                                >

                                    <div style="
                                        color:#947d70;
                                        font-size:10px;
                                        text-transform:uppercase;
                                        letter-spacing:1px;
                                    ">
                                        Order Number
                                    </div>

                                    <div style="
                                        margin-top:7px;
                                        color:#4b2415;
                                        font-size:16px;
                                        font-weight:bold;
                                    ">
                                        {{ $order->order_number }}
                                    </div>

                                    <div style="
                                        margin-top:8px;
                                        color:#947d70;
                                        font-size:11px;
                                    ">
                                        Order Status:
                                        Pending Confirmation
                                    </div>

                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                {{-- ORDER ITEMS --}}
                <tr>
                    <td style="padding:0 30px 25px;">

                        <h2 style="
                            margin:0 0 15px;
                            color:#4b2415;
                            font-family:Georgia,serif;
                            font-size:18px;
                        ">
                            Your Order
                        </h2>

                        <table
                            role="presentation"
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                        >

                            @forelse($order->items as $item)

                                <tr>

                                    <td style="
                                        padding:12px 0;
                                        border-bottom:1px solid #eee5dd;
                                    ">

                                        <strong style="
                                            display:block;
                                            font-size:12px;
                                            line-height:1.5;
                                        ">
                                            {{ $item->product_name }}
                                        </strong>

                                        <span style="
                                            display:block;
                                            margin-top:5px;
                                            color:#8c796e;
                                            font-size:11px;
                                        ">
                                            ₹{{ number_format((float) $item->price, 2) }}
                                            ×
                                            {{ $item->quantity }}
                                        </span>

                                    </td>

                                    <td
                                        align="right"
                                        style="
                                            padding:12px 0;
                                            border-bottom:1px solid #eee5dd;
                                            color:#4b2415;
                                            font-size:12px;
                                            font-weight:bold;
                                            white-space:nowrap;
                                        "
                                    >
                                        ₹{{ number_format((float) $item->total, 2) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td style="
                                        padding:15px 0;
                                        color:#806e63;
                                        font-size:12px;
                                    ">
                                        No order items available.
                                    </td>
                                </tr>

                            @endforelse

                        </table>

                    </td>
                </tr>

                {{-- ORDER TOTALS --}}
                <tr>
                    <td style="padding:0 30px 25px;">

                        <table
                            role="presentation"
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                        >

                            <tr>
                                <td style="
                                    padding:7px 0;
                                    color:#79675d;
                                    font-size:12px;
                                ">
                                    Subtotal
                                </td>

                                <td
                                    align="right"
                                    style="
                                        padding:7px 0;
                                        font-size:12px;
                                    "
                                >
                                    ₹{{ number_format((float) $order->subtotal, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <td style="
                                    padding:7px 0;
                                    color:#79675d;
                                    font-size:12px;
                                ">
                                    Shipping
                                </td>

                                <td
                                    align="right"
                                    style="
                                        padding:7px 0;
                                        font-size:12px;
                                    "
                                >

                                    @if(is_null($order->shipping))

                                        Calculated separately

                                    @elseif((float) $order->shipping === 0.0)

                                        FREE

                                    @else

                                        ₹{{ number_format((float) $order->shipping, 2) }}

                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <td style="
                                    padding:15px 0 0;
                                    border-top:1px solid #dfd4ca;
                                    color:#342117;
                                    font-size:15px;
                                    font-weight:bold;
                                ">
                                    {{ is_null($order->shipping) ? 'Order Subtotal' : 'Total' }}
                                </td>

                                <td
                                    align="right"
                                    style="
                                        padding:15px 0 0;
                                        border-top:1px solid #dfd4ca;
                                        color:#4b2415;
                                        font-size:17px;
                                        font-weight:bold;
                                    "
                                >
                                    ₹{{ number_format((float) $order->total, 2) }}
                                </td>
                            </tr>

                        </table>

                        @if(is_null($order->shipping))

                            <p style="
                                margin:12px 0 0;
                                color:#806e63;
                                font-size:11px;
                                line-height:1.7;
                            ">
                                Shipping charges, if applicable,
                                will be confirmed separately.
                            </p>

                        @endif

                    </td>
                </tr>

                {{-- DELIVERY ADDRESS --}}
                <tr>
                    <td style="padding:0 30px 30px;">

                        <div style="
                            padding:20px;
                            background:#fbf7f2;
                        ">

                            <strong style="
                                display:block;
                                margin-bottom:10px;
                                color:#4b2415;
                                font-family:Georgia,serif;
                                font-size:16px;
                            ">
                                Delivery Address
                            </strong>

                            <p style="
                                margin:0;
                                color:#715f54;
                                font-size:12px;
                                line-height:1.8;
                            ">

                                <strong>{{ $order->name }}</strong>
                                <br>

                                {{ $order->address }}
                                <br>

                                @if($order->landmark)
                                    Landmark:
                                    {{ $order->landmark }}
                                    <br>
                                @endif

                                {{ $order->city }},
                                {{ $order->state }}
                                - {{ $order->pincode }}
                                <br>

                                {{ $order->country }}
                                <br>

                                Phone: {{ $order->phone }}

                            </p>

                        </div>

                    </td>
                </tr>

                {{-- PAYMENT INFORMATION --}}
                <tr>
                    <td style="
                        padding:20px 30px;
                        border-top:1px solid #eee5dd;
                        text-align:center;
                    ">

                        <p style="
                            margin:0 0 8px;
                            color:#806e63;
                            font-size:12px;
                            line-height:1.6;
                        ">
                            Payment Method:
                            <strong style="color:#4b2415;">
                                Cash on Delivery
                            </strong>
                        </p>

                        <p style="
                            margin:0;
                            color:#806e63;
                            font-size:12px;
                            line-height:1.6;
                        ">
                            Payment Status:
                            <strong style="color:#4b2415;">
                                Pending
                            </strong>
                        </p>

                    </td>
                </tr>

                {{-- ADDITIONAL MESSAGE --}}
                <tr>
                    <td style="
                        padding:0 30px 30px;
                        text-align:center;
                    ">

                        <p style="
                            margin:0;
                            color:#766359;
                            font-size:12px;
                            line-height:1.8;
                        ">
                            We'll send you another email
                            when your order is confirmed.

                            Thank you for choosing
                            <strong>The Only Baker</strong>!
                        </p>

                    </td>
                </tr>

                {{-- FOOTER --}}
                <tr>
                    <td
                        align="center"
                        style="
                            padding:25px;
                            background:#3d1d11;
                            color:#d7c2b4;
                            font-size:11px;
                            line-height:1.8;
                        "
                    >

                        <strong style="
                            display:block;
                            margin-bottom:5px;
                            color:#ffffff;
                            font-family:Georgia,serif;
                            font-size:15px;
                        ">
                            The Only Baker
                        </strong>

                        Small Bites. Big Happiness.

                        <div style="
                            margin-top:12px;
                            color:#c5ab9a;
                            font-size:10px;
                        ">
                            &copy; {{ date('Y') }}
                            The Only Baker.
                            All rights reserved.
                        </div>

                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
