<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_inventories', function (Blueprint $table) {

            $table->id();

            $table
                ->string('product_slug', 150)
                ->unique();

            $table
                ->unsignedInteger('stock')
                ->default(0);

            $table
                ->unsignedInteger('low_stock_threshold')
                ->default(5);

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('product_inventories');
    }
};