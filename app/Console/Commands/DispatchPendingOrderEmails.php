<?php

namespace App\Console\Commands;

use App\Jobs\SendOrderEmailNotification;
use App\Models\OrderEmailNotification;
use Illuminate\Console\Command;
use Throwable;

class DispatchPendingOrderEmails extends Command
{
    protected $signature = 'orders:dispatch-pending-emails';

    protected $description =
        'Dispatch pending and safely retryable order emails';

    public function handle(): int
    {
        $count = 0;
        $failedDispatches = 0;

        OrderEmailNotification::query()
            ->whereNull('sent_at')
            ->whereNull('send_started_at')
            ->where('attempts', '<', 5)
            ->whereIn('status', [
                'pending',
                'failed',
            ])
            ->where(function ($query) {
                $query
                    ->whereNull('next_attempt_at')
                    ->orWhere(
                        'next_attempt_at',
                        '<=',
                        now()
                    );
            })
            ->orderBy('id')
            ->chunkById(
                100,
                function ($notifications) use (
                    &$count,
                    &$failedDispatches
                ) {
                    foreach ($notifications as $notification) {
                        try {
                            SendOrderEmailNotification::dispatch(
                                (int) $notification->id
                            );

                            $count++;
                        } catch (Throwable $exception) {
                            report($exception);

                            $failedDispatches++;
                        }
                    }
                }
            );

        $this->info(
            "Dispatched {$count} notification job(s)."
        );

        if ($failedDispatches > 0) {
            $this->warn(
                "{$failedDispatches} dispatch(es) failed."
            );
        }

        return $failedDispatches > 0
            ? self::FAILURE
            : self::SUCCESS;
    }
}
