<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Product;
use Tests\Feature\StoreTest;
use Tests\Feature\ProductTest;

class ProductModelTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $store;

	public function setUp(): void {
		parent::setUp();
		$this->store = StoreTest::__createData();
	}

    /**
     * A basic unit test set price after discount.
     *
     * @return void
     */
    public function testSetPriceAfterDiscount()
    {
    	$model = new Product;
    	$model->price = 1000;
    	$model->discount_type = '';
    	$model->discount = 0;
        $output = Product::setPriceAfterDiscount($model);
    	$expect = new Product;
    	$expect->price = 1000;
    	$expect->discount_type = null;
    	$expect->discount = null;
        $expect->price_after_discount = $expect->price;
        $this->assertEquals($expect, $output);
    }

    /**
     * A basic unit test set price after discount with discount is price.
     *
     * @return void
     */
    public function testSetPriceAfterDiscountWithDiscountIsPrice()
    {
    	$model = new Product;
    	$model->price = 1000;
    	$model->discount_type = 'PRICE';
    	$model->discount = 20;
        $output = Product::setPriceAfterDiscount($model);
    	$expect = new Product;
    	$expect->price = 1000;
    	$expect->discount_type = 'PRICE';
    	$expect->discount = 20;
        $expect->price_after_discount = $expect->price - $expect->discount;
        $this->assertEquals($expect, $output);
    }

    /**
     * A basic unit test set price after discount with discount if percent.
     *
     * @return void
     */
    public function testSetPriceAfterDiscountWithDiscountIsPercent()
    {
    	$model = new Product;
    	$model->price = 1000;
    	$model->discount_type = 'PERCENT';
    	$model->discount = 20;
        $output = Product::setPriceAfterDiscount($model);
    	$expect = new Product;
    	$expect->price = 1000;
    	$expect->discount_type = 'PERCENT';
    	$expect->discount = 20;
        $expect->price_after_discount = $expect->price - (($expect->price * $expect->discount) / 100);
        $this->assertEquals($expect, $output);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDecreaseStock()
    {
        $model = ProductTest::__createData($this->store->id);
        $model->load(['flashSales']);
        $output = Product::decreaseStock($model, 2)->stock;
        $expect = 98;
        $this->assertEquals($expect, $output);
    }
}
