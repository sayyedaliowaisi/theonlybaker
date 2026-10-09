<?php

namespace App\Console\Commands;

use App\Jobs\SendOrderEmailNotification;
use App\Models\OrderEmailNotification;
use Illuminate\Console\Command;

class DispatchPendingOrderEmails extends Command
{
    protected $signature = 'orders:dispatch-pending-emails';

    protected $description =
        'Dispatch pending and safely retryable order emails';

    public function handle(): int
    {
        $count = 0;

        OrderEmailNotification::query()
            ->whereNull('sent_at')
            ->where('attempts', '<', 5)
            ->where(function ($query) {
                $query
                    ->where(function ($pending) {
                        $pending
                            ->where('status', 'pending')
                            ->whereNull('send_started_at');
                    })
                    ->orWhere(function ($failed) {
                        $failed
                            ->where('status', 'failed')
                            ->whereNull('send_started_at')
                            ->whereNotNull('next_attempt_at')
                            ->where(
                                'next_attempt_at',
                                '<=',
                                now()
                            );
                    });
            })
            ->orderBy('id')
            ->chunkById(
                100,
                function ($notifications) use (&$count) {
                    foreach ($notifications as $notification) {
                        SendOrderEmailNotification::dispatch(
                            $notification->id
                        );

                        $count++;
                    }
                }
            );

        $this->info(
            "Dispatched {$count} notification job(s)."
        );

        return self::SUCCESS;
    }
}
