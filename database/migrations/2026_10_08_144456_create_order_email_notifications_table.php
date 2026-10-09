<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_email_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->string('type', 30);
            $table->string('recipient_email');
            $table->string('status', 20)->default('pending');

            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('next_attempt_at')->nullable();
            $table->timestamp('processing_started_at')->nullable();
            $table->timestamp('sent_at')->nullable();

            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'type']);
            $table->index(['status', 'next_attempt_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_email_notifications');
    }
};