<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            $table->string('order_number')->unique();

            $table->string('name');
            $table->string('email');
            $table->string('phone', 20);

            $table->text('address');
            $table->string('landmark')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pincode', 10);
            $table->string('country')->default('India');

            $table->string('payment_method')->default('cod');
            $table->string('payment_status')->default('pending');
            $table->string('order_status')->default('pending');

            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};