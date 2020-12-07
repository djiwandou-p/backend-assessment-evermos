<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->char('sku');
            $table->string('name');
            $table->unsignedInteger('stock')->default(0);
            $table->float('price', 10, 2)->default(0);
            $table->enum('discount_type', ['PRICE', 'PERCENT'])->nullable()->default(null);
            $table->float('discount', 10, 2)->nullable()->default(0);
            $table->float('price_after_discount', 10, 2)->nullable()->default(0);
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('products');
    }
}
