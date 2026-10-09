<?php

namespace Tests\Feature;

use App\Jobs\SendOrderEmailNotification;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderEmailNotification;
use App\Models\OrderEmailRecoveryLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class AdminEmailRecoveryTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Test Administrator',
            'email' => 'admin@example.test',
            'password' => 'TestPassword123!',
        ]);
    }

    private function makeNotification(
        array $attributes = []
    ): OrderEmailNotification {
        $order = Order::create([
            'order_number' => 'TOB-SEC-' . uniqid(),
            'name' => 'Security Test Customer',
            'email' => 'customer@example.test',
            'phone' => '9876543210',
            'address' => 'Test Address',
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
                'status' => 'failed',
                'attempts' => 2,
                'next_attempt_at' => now()->addMinutes(10),
                'last_error' => 'Simulated mail error',
            ], $attributes)
        );
    }

    private function recoveryPayload(
        array $overrides = []
    ): array {
        return array_merge([
            'delivery_verification' => 'confirmed_not_delivered',
            'reason' => 'Verified non-delivery using mail provider records.',
            'worker_stopped' => '1',
        ], $overrides);
    }

    public function test_guest_cannot_access_admin_investigation(): void
    {
        $notification = $this->makeNotification();

        $response = $this->get(
            route('admin.email-notifications.show', $notification)
        );

        $this->assertContains(
            $response->status(),
            [302, 401, 403]
        );
    }

    public function test_customer_guard_cannot_recover_email(): void
    {
        Bus::fake();

        $notification = $this->makeNotification();

        $response = $this->post(
            route('admin.email-notifications.retry', $notification),
            $this->recoveryPayload()
        );

        $this->assertContains(
            $response->status(),
            [302, 401, 403]
        );

        $this->assertDatabaseHas(
            'order_email_notifications',
            [
                'id' => $notification->id,
                'status' => 'failed',
            ]
        );

        Bus::assertNotDispatched(
            SendOrderEmailNotification::class
        );
    }

    public function test_admin_can_view_investigation_page(): void
    {
        $admin = $this->makeAdmin();

        $notification = $this->makeNotification();

        $this->actingAs($admin, 'admin')
            ->get(
                route('admin.email-notifications.show', $notification)
            )
            ->assertOk()
            ->assertSee('Notification Details')
            ->assertSee('Recovery Audit History');
    }

    public function test_admin_cannot_recover_without_verified_non_delivery(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();
        $notification = $this->makeNotification();

        $this->actingAs($admin, 'admin')
            ->from(
                route('admin.email-notifications.show', $notification)
            )
            ->post(
                route('admin.email-notifications.retry', $notification),
                $this->recoveryPayload([
                    'delivery_verification' => 'delivery_uncertain',
                ])
            )
            ->assertSessionHasErrors('delivery_verification');

        $this->assertDatabaseHas(
            'order_email_notifications',
            [
                'id' => $notification->id,
                'status' => 'failed',
            ]
        );

        Bus::assertNotDispatched(
            SendOrderEmailNotification::class
        );
    }

    public function test_admin_cannot_recover_without_worker_confirmation(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();
        $notification = $this->makeNotification();

        $payload = $this->recoveryPayload();

        unset($payload['worker_stopped']);

        $this->actingAs($admin, 'admin')
            ->post(
                route('admin.email-notifications.retry', $notification),
                $payload
            )
            ->assertSessionHasErrors('worker_stopped');

        $this->assertDatabaseHas(
            'order_email_notifications',
            [
                'id' => $notification->id,
                'status' => 'failed',
            ]
        );

        Bus::assertNotDispatched(
            SendOrderEmailNotification::class
        );
    }

    public function test_admin_can_recover_verified_failed_email(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();
        $notification = $this->makeNotification();

        $this->actingAs($admin, 'admin')
            ->post(
                route('admin.email-notifications.retry', $notification),
                $this->recoveryPayload()
            )
            ->assertRedirect(
                route('admin.email-notifications.show', $notification)
            );

        $notification->refresh();

        $this->assertSame('pending', $notification->status);
        $this->assertSame(0, $notification->attempts);
        $this->assertNull($notification->next_attempt_at);
        $this->assertNull($notification->processing_token);
        $this->assertNull($notification->send_started_at);

        $this->assertDatabaseHas(
            'order_email_recovery_logs',
            [
                'notification_id' => $notification->id,
                'admin_id' => $admin->id,
                'action' => 'retry_failed',
                'previous_status' => 'failed',
                'new_status' => 'pending',
                'delivery_verification' => 'confirmed_not_delivered',
            ]
        );

        Bus::assertDispatched(
            SendOrderEmailNotification::class,
            fn ($job) =>
                $job->notificationId === $notification->id
        );
    }

    public function test_admin_can_recover_verified_uncertain_email(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();

        $notification = $this->makeNotification([
            'status' => 'delivery_uncertain',
            'attempts' => 1,
            'next_attempt_at' => null,
            'send_started_at' => now()->subMinutes(10),
        ]);

        $this->actingAs($admin, 'admin')
            ->post(
                route(
                    'admin.email-notifications.recover-uncertain',
                    $notification
                ),
                $this->recoveryPayload()
            )
            ->assertRedirect(
                route('admin.email-notifications.show', $notification)
            );

        $notification->refresh();

        $this->assertSame('pending', $notification->status);
        $this->assertNull($notification->send_started_at);

        $this->assertDatabaseHas(
            'order_email_recovery_logs',
            [
                'notification_id' => $notification->id,
                'admin_id' => $admin->id,
                'action' => 'recover_uncertain',
            ]
        );

        Bus::assertDispatched(
            SendOrderEmailNotification::class
        );
    }

    public function test_recent_processing_email_cannot_be_recovered(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();

        $notification = $this->makeNotification([
            'status' => 'processing',
            'attempts' => 1,
            'processing_started_at' => now(),
            'processing_token' => 'active-worker-token',
        ]);

        $this->actingAs($admin, 'admin')
            ->post(
                route('admin.email-notifications.recover', $notification),
                $this->recoveryPayload()
            )
            ->assertSessionHasErrors('notification');

        $notification->refresh();

        $this->assertSame('processing', $notification->status);
        $this->assertSame(
            'active-worker-token',
            $notification->processing_token
        );

        Bus::assertNotDispatched(
            SendOrderEmailNotification::class
        );
    }

    public function test_stuck_processing_email_can_be_recovered(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();

        $notification = $this->makeNotification([
            'status' => 'processing',
            'attempts' => 1,
            'processing_started_at' => now()->subMinutes(10),
            'processing_token' => 'stale-worker-token',
            'send_started_at' => now()->subMinutes(10),
        ]);

        $this->actingAs($admin, 'admin')
            ->post(
                route('admin.email-notifications.recover', $notification),
                $this->recoveryPayload()
            )
            ->assertRedirect(
                route('admin.email-notifications.show', $notification)
            );

        $notification->refresh();

        $this->assertSame('pending', $notification->status);
        $this->assertNull($notification->processing_token);
        $this->assertNull($notification->send_started_at);

        $this->assertDatabaseHas(
            'order_email_recovery_logs',
            [
                'notification_id' => $notification->id,
                'admin_id' => $admin->id,
                'action' => 'recover_processing',
            ]
        );
    }

    public function test_sent_email_cannot_be_recovered(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();

        $notification = $this->makeNotification([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $this->actingAs($admin, 'admin')
            ->post(
                route('admin.email-notifications.retry', $notification),
                $this->recoveryPayload()
            )
            ->assertSessionHasErrors('notification');

        $this->assertDatabaseHas(
            'order_email_notifications',
            [
                'id' => $notification->id,
                'status' => 'sent',
            ]
        );

        Bus::assertNotDispatched(
            SendOrderEmailNotification::class
        );
    }

    public function test_recovery_reason_must_be_at_least_ten_characters(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();
        $notification = $this->makeNotification();

        $this->actingAs($admin, 'admin')
            ->post(
                route('admin.email-notifications.retry', $notification),
                $this->recoveryPayload([
                    'reason' => 'short',
                ])
            )
            ->assertSessionHasErrors('reason');

        $this->assertSame(
            0,
            OrderEmailRecoveryLog::count()
        );
    }
}
