
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // STEP 1: Add new columns
        Schema::table('order_email_notifications', function (Blueprint $table) {
            $table->uuid('processing_token')->nullable();

            $table->timestamp('send_started_at')->nullable();

            $table->index(
                ['status', 'processing_started_at'],
                'order_email_processing_status_idx'
            );
        });

        // STEP 2: Mark old failed emails as uncertain
        DB::table('order_email_notifications')
            ->where('status', 'failed')
            ->update([
                'status' => 'delivery_uncertain',
                'next_attempt_at' => null,
            ]);
    }

    public function down(): void
    {
        Schema::table('order_email_notifications', function (Blueprint $table) {
            $table->dropIndex('order_email_processing_status_idx');

            $table->dropColumn([
                'processing_token',
                'send_started_at',
            ]);
        });
    }
};
