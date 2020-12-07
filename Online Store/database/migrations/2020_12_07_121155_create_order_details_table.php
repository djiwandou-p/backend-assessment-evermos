<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('qty')->default(0);
            $table->float('price', 10, 2)->default(0);
            $table->enum('discount_type', ['PRICE', 'PERCENT'])->nullable()->default(null);
            $table->float('discount', 10, 2)->nullable()->default(0);
            $table->float('price_after_discount', 10, 2)->nullable()->default(0);
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('is_flash_sale')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
