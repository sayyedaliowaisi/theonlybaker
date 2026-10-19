@php
    $messages = [
        'order_confirmed' => [
            'title' => 'Your Order Is Confirmed!',
            'message' => 'Your order has been confirmed and will be prepared soon.',
        ],
        'order_shipped' => [
            'title' => 'Your Order Is On Its Way!',
            'message' => 'Your order has been shipped and is on its way to you.',
        ],
        'order_delivered' => [
            'title' => 'Your Order Has Been Delivered!',
            'message' => 'Your order has been marked as delivered. Thank you for shopping with us!',
        ],
        'order_cancelled' => [
            'title' => 'Your Order Has Been Cancelled',
            'message' => 'Your order has been cancelled. Please contact us if you have any questions.',
        ],
        'payment_received' => [
            'title' => 'Payment Received!',
            'message' => 'We have received your Cash on Delivery payment. Thank you!',
        ],
    ];

    $content = $messages[$notificationType] ?? [
        'title' => 'Order Update',
        'message' => 'There is an update regarding your order.',
    ];
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $content['title'] }}</title>
</head>
<body style="margin:0;padding:30px 15px;background:#f8f5f1;font-family:Arial,sans-serif;color:#382c27;">

    <table role="presentation" cellpadding="0" cellspacing="0"
           style="width:100%;max-width:600px;margin:0 auto;background:#fff;border-radius:12px;">
        <tr>
            <td style="padding:30px;">

                <h2 style="margin:0 0 8px;color:#8b5e3c;">
                    The Only Baker
                </h2>

                <h1 style="font-size:23px;margin:25px 0 15px;">
                    {{ $content['title'] }}
                </h1>

                <p>Hello {{ $order->name }},</p>

                <p style="line-height:1.7;">
                    {{ $content['message'] }}
                </p>

                <div style="background:#faf5ef;padding:18px;border-radius:8px;margin:25px 0;">
                    <p style="margin:0 0 10px;">
                        <strong>Order Number:</strong>
                        {{ $order->order_number }}
                    </p>

                    <p style="margin:0 0 10px;">
                        <strong>Order Status:</strong>
                        {{ ucfirst($order->order_status) }}
                    </p>

                    <p style="margin:0 0 10px;">
                        <strong>Payment Status:</strong>
                        {{ ucfirst($order->payment_status) }}
                    </p>

                    <p style="margin:0;">
                        <strong>Order Amount:</strong>
                        ₹{{ number_format($order->total, 2) }}
                    </p>
                </div>

                <p style="font-size:13px;color:#806d61;">
                    Thank you for choosing The Only Baker.
                </p>

            </td>
        </tr>
    </table>

</body>
</html>