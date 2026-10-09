<?php

namespace Tests\Feature;

use App\Jobs\SendOrderEmailNotification;
use App\Models\Order;
use App\Models\OrderEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailNotificationJobTest extends TestCase
{
    use RefreshDatabase;

    private function createNotification(
        array $attributes = []
    ): OrderEmailNotification {
        $order = Order::create([
            'order_number' => 'TOB-TEST-' . uniqid(),
            'name' => 'Test Customer',
            'email' => 'customer@example.test',
            'phone' => '9876543210',
            'address' => 'Test Street',
            'city' => 'Lucknow',
            'state' => 'Uttar Pradesh',
            'pincode' => '226001',
            'country' => 'India',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'subtotal' => 500,
            'shipping' => 0,
            'total' => 500,
        ]);

        return OrderEmailNotification::create(
            array_merge([
                'order_id' => $order->id,
                'type' => 'customer',
                'recipient_email' => 'customer@example.test',
                'status' => 'pending',
                'attempts' => 0,
            ], $attributes)
        );
    }

    public function test_pending_email_is_sent_successfully(): void
    {
        Mail::fake();

        $notification = $this->createNotification();

        (new SendOrderEmailNotification(
            $notification->id
        ))->handle();

        $notification->refresh();

        $this->assertSame('sent', $notification->status);
        $this->assertSame(1, $notification->attempts);
        $this->assertNotNull($notification->sent_at);
        $this->assertNull($notification->processing_token);

        Mail::assertSent(
            \App\Mail\CustomerOrderConfirmation::class,
            1
        );
    }

    public function test_already_sent_email_is_not_sent_again(): void
    {
        Mail::fake();

        $notification = $this->createNotification();

        $job = new SendOrderEmailNotification(
            $notification->id
        );

        $job->handle();
        $job->handle();

        $notification->refresh();

        $this->assertSame('sent', $notification->status);
        $this->assertSame(1, $notification->attempts);

        Mail::assertSent(
            \App\Mail\CustomerOrderConfirmation::class,
            1
        );
    }

    public function test_uncertain_email_is_not_sent_automatically(): void
    {
        Mail::fake();

        $notification = $this->createNotification([
            'status' => 'delivery_uncertain',
            'attempts' => 1,
            'send_started_at' => now(),
        ]);

        (new SendOrderEmailNotification(
            $notification->id
        ))->handle();

        $notification->refresh();

        $this->assertSame(
            'delivery_uncertain',
            $notification->status
        );

        $this->assertSame(1, $notification->attempts);

        Mail::assertNothingSent();
    }

    public function test_processing_email_is_not_claimed_again(): void
    {
        Mail::fake();

        $notification = $this->createNotification([
            'status' => 'processing',
            'attempts' => 1,
            'processing_started_at' => now(),
            'processing_token' => 'existing-worker-token',
        ]);

        (new SendOrderEmailNotification(
            $notification->id
        ))->handle();

        $notification->refresh();

        $this->assertSame('processing', $notification->status);
        $this->assertSame(1, $notification->attempts);
        $this->assertSame(
            'existing-worker-token',
            $notification->processing_token
        );

        Mail::assertNothingSent();
    }
}
