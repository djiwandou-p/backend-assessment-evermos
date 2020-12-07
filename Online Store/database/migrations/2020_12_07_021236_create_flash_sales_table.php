<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->datetime('start_at')->nullable()->default(null);
            $table->datetime('end_at')->nullable()->default(null);
            $table->unsignedInteger('stock')->default(0);
            $table->float('price', 10, 2)->default(0);
            $table->enum('discount_type', ['PRICE', 'PERCENT'])->nullable()->default(null);
            $table->float('discount', 10, 2)->nullable()->default(0);
            $table->float('price_after_discount', 10, 2)->nullable()->default(0);
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('flash_sales');
    }
}
