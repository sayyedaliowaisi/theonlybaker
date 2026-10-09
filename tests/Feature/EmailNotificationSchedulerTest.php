<?php

namespace Tests\Feature;

use App\Jobs\SendOrderEmailNotification;
use App\Models\Order;
use App\Models\OrderEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class EmailNotificationSchedulerTest extends TestCase
{
    use RefreshDatabase;

    private function makeNotification(
        string $status,
        array $extra = []
    ): OrderEmailNotification {
        $order = Order::create([
            'order_number' => 'TOB-' . uniqid(),
            'name' => 'Scheduler Customer',
            'email' => 'scheduler@example.test',
            'phone' => '9876543210',
            'address' => 'Test Address',
            'city' => 'Lucknow',
            'state' => 'Uttar Pradesh',
            'pincode' => '226001',
            'subtotal' => 300,
            'shipping' => 0,
            'total' => 300,
        ]);

        return OrderEmailNotification::create(
            array_merge([
                'order_id' => $order->id,
                'type' => 'customer',
                'recipient_email' => 'scheduler@example.test',
                'status' => $status,
                'attempts' => 0,
            ], $extra)
        );
    }

    public function test_scheduler_only_dispatches_safe_notifications(): void
    {
        Bus::fake();

        $pending = $this->makeNotification('pending');

        $failed = $this->makeNotification('failed', [
            'attempts' => 1,
            'next_attempt_at' => now()->subMinute(),
        ]);

        $this->makeNotification('sent', [
            'sent_at' => now(),
        ]);

        $this->makeNotification('processing', [
            'attempts' => 1,
            'processing_started_at' => now()->subMinutes(10),
        ]);

        $this->makeNotification('delivery_uncertain', [
            'attempts' => 1,
            'send_started_at' => now(),
        ]);

        $this->artisan('orders:dispatch-pending-emails')
            ->assertSuccessful();

        Bus::assertDispatched(
            SendOrderEmailNotification::class,
            2
        );

        Bus::assertDispatched(
            SendOrderEmailNotification::class,
            fn ($job) => $job->notificationId === $pending->id
        );

        Bus::assertDispatched(
            SendOrderEmailNotification::class,
            fn ($job) => $job->notificationId === $failed->id
        );
    }

    public function test_scheduler_skips_failed_email_not_yet_due(): void
    {
        Bus::fake();

        $this->makeNotification('failed', [
            'attempts' => 1,
            'next_attempt_at' => now()->addMinutes(10),
        ]);

        $this->artisan('orders:dispatch-pending-emails')
            ->assertSuccessful();

        Bus::assertNotDispatched(
            SendOrderEmailNotification::class
        );
    }

    public function test_scheduler_skips_exhausted_attempts(): void
    {
        Bus::fake();

        $this->makeNotification('failed', [
            'attempts' => 5,
            'next_attempt_at' => now()->subMinute(),
        ]);

        $this->artisan('orders:dispatch-pending-emails')
            ->assertSuccessful();

        Bus::assertNotDispatched(
            SendOrderEmailNotification::class
        );
    }
}
