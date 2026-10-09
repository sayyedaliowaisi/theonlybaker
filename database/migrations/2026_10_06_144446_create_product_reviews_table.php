<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('product_slug', 150);

            $table->unsignedTinyInteger('rating');

            $table->text('review')->nullable();

            $table->timestamps();

            $table->unique(
                ['user_id', 'product_slug'],
                'product_reviews_user_product_unique'
            );

            $table->index('product_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};