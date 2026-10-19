<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerOrderUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $notificationType
    ) {
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'order_confirmed' => 'Order Confirmed',
            'order_shipped' => 'Order Shipped',
            'order_delivered' => 'Order Delivered',
            'order_cancelled' => 'Order Cancelled',
            'payment_received' => 'Payment Received',
        ];

        $subject = $subjects[$this->notificationType] ?? 'Order Update';

        return new Envelope(
            subject: $subject
                . ' - '
                . $this->order->order_number
                . ' | The Only Baker'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-order-update'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
