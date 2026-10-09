<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Order Confirmed
    </title>

</head>


<body
    style="
        margin:0;
        padding:0;
        background:#f7f1eb;
        font-family:Arial,Helvetica,sans-serif;
        color:#342117;
    "
>

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="background:#f7f1eb;padding:30px 15px;"
>

<tr>

<td align="center">

<table
    width="620"
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

            <div
                style="
                    color:#d8bba7;
                    font-size:10px;
                    font-weight:bold;
                    letter-spacing:2px;
                "
            >
                THE ONLY BAKER
            </div>


            <h1
                style="
                    margin:8px 0 0;
                    color:#ffffff;
                    font-family:Georgia,serif;
                    font-size:28px;
                    font-weight:normal;
                "
            >
                Order Confirmed
            </h1>

        </td>

    </tr>


    {{-- MESSAGE --}}
    <tr>

        <td style="padding:32px 30px 20px;">

            <p
                style="
                    margin:0 0 10px;
                    font-size:15px;
                    font-weight:bold;
                "
            >
                Hi {{ $order->name }},
            </p>


            <p
                style="
                    margin:0;
                    color:#766359;
                    font-size:13px;
                    line-height:1.7;
                "
            >
                Thank you for ordering from The Only Baker.
                We've received your order and will prepare
                your freshly baked treats with care.
            </p>

        </td>

    </tr>


    {{-- ORDER NUMBER --}}
    <tr>

        <td style="padding:0 30px 25px;">

            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                border="0"
                style="background:#f7f0e9;"
            >

                <tr>

                    <td
                        align="center"
                        style="padding:17px;"
                    >

                        <div
                            style="
                                color:#947d70;
                                font-size:9px;
                                text-transform:uppercase;
                                letter-spacing:1px;
                            "
                        >
                            Order Number
                        </div>


                        <div
                            style="
                                margin-top:5px;
                                color:#4b2415;
                                font-size:15px;
                                font-weight:bold;
                            "
                        >
                            {{ $order->order_number }}
                        </div>

                    </td>

                </tr>

            </table>

        </td>

    </tr>


    {{-- ITEMS --}}
    <tr>

        <td style="padding:0 30px 25px;">

            <h2
                style="
                    margin:0 0 15px;
                    color:#4b2415;
                    font-family:Georgia,serif;
                    font-size:18px;
                "
            >
                Your Order
            </h2>


            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                border="0"
            >

                @foreach($order->items as $item)

                    <tr>

                        <td
                            style="
                                padding:12px 0;
                                border-bottom:1px solid #eee5dd;
                            "
                        >

                            <strong
                                style="
                                    display:block;
                                    font-size:12px;
                                "
                            >
                                {{ $item->product_name }}
                            </strong>


                            <span
                                style="
                                    display:block;
                                    margin-top:4px;
                                    color:#8c796e;
                                    font-size:10px;
                                "
                            >
                                ₹{{ number_format($item->price, 0) }}
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
                            "
                        >
                            ₹{{ number_format($item->total, 0) }}
                        </td>

                    </tr>

                @endforeach

            </table>

        </td>

    </tr>


    {{-- TOTALS --}}
    <tr>

        <td style="padding:0 30px 25px;">

            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                border="0"
            >

                <tr>

                    <td
                        style="
                            padding:6px 0;
                            color:#79675d;
                            font-size:11px;
                        "
                    >
                        Subtotal
                    </td>

                    <td
                        align="right"
                        style="
                            padding:6px 0;
                            font-size:11px;
                        "
                    >
                        ₹{{ number_format($order->subtotal, 0) }}
                    </td>

                </tr>


                <tr>

                    <td
                        style="
                            padding:6px 0;
                            color:#79675d;
                            font-size:11px;
                        "
                    >
                        Shipping
                    </td>

                    <td
                        align="right"
                        style="
                            padding:6px 0;
                            font-size:11px;
                        "
                    >

                        @if(is_null($order->shipping))

                            Calculated separately

                        @elseif((float) $order->shipping === 0.0)

                            FREE

                        @else

                            ₹{{ number_format($order->shipping, 0) }}

                        @endif

                    </td>

                </tr>


                <tr>

                    <td
                        style="
                            padding:13px 0 0;
                            border-top:1px solid #dfd4ca;
                            color:#342117;
                            font-size:14px;
                            font-weight:bold;
                        "
                    >
                        {{ is_null($order->shipping) ? 'Order Subtotal' : 'Total' }}
                    </td>

                    <td
                        align="right"
                        style="
                            padding:13px 0 0;
                            border-top:1px solid #dfd4ca;
                            color:#4b2415;
                            font-size:16px;
                            font-weight:bold;
                        "
                    >
                        ₹{{ number_format($order->total, 0) }}
                    </td>

                </tr>

            </table>


            @if(is_null($order->shipping))

                <p
                    style="
                        margin:12px 0 0;
                        color:#806e63;
                        font-size:10px;
                        line-height:1.6;
                    "
                >
                    Shipping charges, if applicable,
                    will be confirmed separately.
                </p>

            @endif

        </td>

    </tr>


    {{-- DELIVERY --}}
    <tr>

        <td style="padding:0 30px 30px;">

            <div
                style="
                    padding:18px;
                    background:#fbf7f2;
                "
            >

                <strong
                    style="
                        display:block;
                        margin-bottom:8px;
                        color:#4b2415;
                        font-family:Georgia,serif;
                        font-size:15px;
                    "
                >
                    Delivery Address
                </strong>


                <p
                    style="
                        margin:0;
                        color:#715f54;
                        font-size:11px;
                        line-height:1.7;
                    "
                >

                    {{ $order->name }}<br>

                    {{ $order->address }}<br>

                    @if($order->landmark)

                        {{ $order->landmark }}<br>

                    @endif

                    {{ $order->city }},
                    {{ $order->state }}
                    - {{ $order->pincode }}<br>

                    {{ $order->country }}<br>

                    {{ $order->phone }}

                </p>

            </div>

        </td>

    </tr>


    {{-- PAYMENT --}}
    <tr>

        <td
            style="
                padding:20px 30px;
                border-top:1px solid #eee5dd;
                color:#806e63;
                font-size:11px;
                text-align:center;
            "
        >

            Payment Method:

            <strong style="color:#4b2415;">
                Cash on Delivery
            </strong>

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
                font-size:10px;
                line-height:1.7;
            "
        >

            <strong
                style="
                    display:block;
                    margin-bottom:4px;
                    color:#ffffff;
                    font-family:Georgia,serif;
                    font-size:14px;
                "
            >
                The Only Baker
            </strong>

            Small Bites. Big Happiness.

        </td>

    </tr>

</table>

</td>

</tr>

</table>

</body>

</html>