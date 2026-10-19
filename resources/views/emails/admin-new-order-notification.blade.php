
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Order Received | The Only Baker</title>
</head>

<body style="margin:0;padding:30px 15px;background:#f8f5f1;font-family:Arial,Helvetica,sans-serif;color:#382c27;">

    <table role="presentation" cellpadding="0" cellspacing="0"
           width="100%"
           style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;border-collapse:separate;overflow:hidden;">

        <tr>
            <td style="padding:25px 30px;background:#8b5e3c;text-align:center;">
                <h2 style="margin:0;color:#ffffff;font-size:26px;">
                    The Only Baker
                </h2>
                <p style="margin:8px 0 0;color:#f5e7d7;font-size:13px;">
                    New Order Notification
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding:30px;">

                <h1 style="font-size:23px;color:#8b5e3c;margin:0 0 18px;">
                    New Order Received!
                </h1>

                <p style="font-size:15px;line-height:1.7;">
                    A new order has been placed on The Only Baker.
                </p>

                <div style="background:#faf5ef;padding:20px;border-radius:8px;margin:25px 0;">

                    <h3 style="margin:0 0 18px;color:#8b5e3c;">
                        Order Details
                    </h3>

                    <p>
                        <strong>Order Number:</strong>
                        {{ $order->order_number }}
                    </p>

                    <p>
                        <strong>Customer:</strong>
                        {{ $order->name }}
                    </p>

                    <p>
                        <strong>Email:</strong>
                        {{ $order->email }}
                    </p>

                    <p>
                        <strong>Phone:</strong>
                        {{ $order->phone }}
                    </p>

                    <p>
                        <strong>Order Type:</strong>
                        {{ strtoupper($order->order_type ?? 'B2C') }}
                    </p>

                    <p>
                        <strong>Payment Method:</strong>
                        {{ strtoupper($order->payment_method ?? 'COD') }}
                    </p>

                    <p>
                        <strong>Payment Status:</strong>
                        {{ ucfirst($order->payment_status) }}
                    </p>

                    <p style="margin-bottom:0;">
                        <strong>Order Total:</strong>
                        ₹{{ number_format((float) $order->total, 2) }}
                    </p>

                </div>

                <p style="font-size:15px;line-height:1.7;">
                    Please review this order in the admin dashboard
                    and process it accordingly.
                </p>

            </td>
        </tr>

        <tr>
            <td style="padding:22px 30px;background:#f3e9de;text-align:center;">
                <p style="margin:0 0 8px;font-size:13px;color:#806d61;">
                    Automated Admin Order Notification
                </p>
                <p style="margin:0;font-size:12px;color:#806d61;">
                    &copy; {{ date('Y') }} The Only Baker.
                    All rights reserved.
                </p>
            </td>
        </tr>

    </table>

</body>
</html>
