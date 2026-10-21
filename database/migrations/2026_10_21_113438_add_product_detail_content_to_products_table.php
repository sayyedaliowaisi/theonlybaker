<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('subtitle')->nullable();
            $table->boolean('show_premium_badge')->default(true);
            $table->string('premium_badge_text')->nullable();

            $table->text('ingredients')->nullable();
            $table->text('nutrition_information')->nullable();
            $table->text('storage_instructions')->nullable();
            $table->text('shipping_delivery')->nullable();

            for ($i = 1; $i <= 4; $i++) {
                $table->string("benefit_{$i}_icon")->nullable();
                $table->string("benefit_{$i}_title")->nullable();
                $table->text("benefit_{$i}_description")->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'subtitle',
                'show_premium_badge',
                'premium_badge_text',
                'ingredients',
                'nutrition_information',
                'storage_instructions',
                'shipping_delivery',
            ];

            for ($i = 1; $i <= 4; $i++) {
                $columns[] = "benefit_{$i}_icon";
                $columns[] = "benefit_{$i}_title";
                $columns[] = "benefit_{$i}_description";
            }

            $table->dropColumn($columns);
        });
    }
};