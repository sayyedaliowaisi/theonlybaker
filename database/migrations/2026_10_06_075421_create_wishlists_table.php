<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('product_slug', 120);

            $table->timestamps();

            $table->unique([
                'user_id',
                'product_slug',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};