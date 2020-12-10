<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\StoreTest;
use Tests\Feature\ProductTest;
use Tests\Feature\OrderTest;
use App\Http\Resources\OrderDetailResource;

class OrderDetailResourceTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $store;
    private $product;
    private $order;

	public function setUp(): void {
		parent::setUp();
		$this->store = StoreTest::__createData();
		$this->product = ProductTest::__createData($this->store->id);
		$this->order = OrderTest::__createData();
		OrderTest::$fieldValuesDetail['qty'] = $this->product->stock;
		OrderTest::$fieldValuesDetail['price'] = $this->product->price;
		OrderTest::$fieldValuesDetail['discount_type'] = $this->product->discount_type;
		OrderTest::$fieldValuesDetail['discount'] = $this->product->discount;
		OrderTest::$fieldValuesDetail['price_after_discount'] = $this->product->price_after_discount;
	}


    /**
     * An unit test to get order detail resource
     *
     * @return void
     */
    public function testGetOrderDetailResource()
    {
        $resources = OrderDetailResource::collection([]);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [];
        $this->assertEquals($expect, $output);

        $model = OrderTest::__createDataDetail($this->order->id, $this->product->id);
        $resources = new OrderDetailResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
	        'id' => $model->id,
	        'order_id' => $model->order_id,
	        'qty' => $model->qty,
	        'price' => $model->price,
	        'discount_type' => $model->discount_type,
	        'discount' => $model->discount,
	        'price_after_discount' => $model->price_after_discount,
	        'product_id' => $model->product_id,
	        'is_flash_sale' => $model->is_flash_sale,
	        'product' => [
		        'id' => $this->product->id,
		        'name' => $this->product->name,
		        'sku' => $this->product->sku,
		        'stock' => $this->product->stock,
		        'price' => $this->product->price,
		        'discount_type' => $this->product->discount_type,
		        'discount' => $this->product->discount,
		        'price_after_discount' => $this->product->price_after_discount,
	        ]
		];
        $this->assertEquals($expect, $output);
    }
}
