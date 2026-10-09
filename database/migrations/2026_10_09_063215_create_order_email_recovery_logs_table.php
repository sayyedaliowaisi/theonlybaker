<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_email_recovery_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('notification_id')
                ->constrained('order_email_notifications')
                ->cascadeOnDelete();

            // Admin model ki key ko generic rakha hai.
            $table->unsignedBigInteger('admin_id');

            $table->string('action', 40);
            $table->string('previous_status', 20);
            $table->string('new_status', 20);

            $table->text('reason');
            $table->string('delivery_verification', 40);

            $table->timestamp('created_at')->useCurrent();

            $table->index(['notification_id', 'created_at']);
            $table->index('admin_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_email_recovery_logs');
    }
};