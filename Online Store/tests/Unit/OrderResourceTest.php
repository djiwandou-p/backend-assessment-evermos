<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\StoreTest;
use Tests\Feature\ProductTest;
use Tests\Feature\OrderTest;
use App\Http\Resources\OrderResource;

class OrderResourceTest extends TestCase
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
        OrderTest::$fieldValuesDetail['qty'] = $this->product->stock;
        OrderTest::$fieldValuesDetail['price'] = $this->product->price;
        OrderTest::$fieldValuesDetail['discount_type'] = $this->product->discount_type;
        OrderTest::$fieldValuesDetail['discount'] = $this->product->discount;
        OrderTest::$fieldValuesDetail['price_after_discount'] = $this->product->price_after_discount;
    }

    /**
     * An unit test to get order resource
     *
     * @return void
     */
    public function testGetOrderResource()
    {
        $resources = OrderResource::collection([]);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [];
        $this->assertEquals($expect, $output);

        $model = OrderTest::__createData();
        $resources = new OrderResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
			"email" => $model->email,
			"delivery_to" => $model->delivery_to,
			"status" => $model->status,
			"details" => []
		];
        $this->assertEquals($expect, $output);
    }

    /**
     * An unit test to get order resource with order detail
     *
     * @return void
     */
    public function testGetOrderResourceWithOrderDetail()
    {
        $resources = OrderResource::collection([]);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [];
        $this->assertEquals($expect, $output);

        $model = OrderTest::__createData();
        $resources = new OrderResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
            "id" => $model->id,
            "name" => $model->name,
            "email" => $model->email,
            "delivery_to" => $model->delivery_to,
            "status" => $model->status,
            "details" => []
        ];
        $this->assertEquals($expect, $output);

        $modelDetail = OrderTest::__createDataDetail($model->id, $this->product->id);
        $model->load('orderDetails');
        $resources = new OrderResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
            "id" => $model->id,
            "name" => $model->name,
            "email" => $model->email,
            "delivery_to" => $model->delivery_to,
            "status" => $model->status,
            "details" =>[
                [
                    'id' => $modelDetail->id,
                    'order_id' => $modelDetail->order_id,
                    'qty' => $modelDetail->qty,
                    'price' => $modelDetail->price,
                    'discount_type' => $modelDetail->discount_type,
                    'discount' => $modelDetail->discount,
                    'price_after_discount' => $modelDetail->price_after_discount,
                    'product_id' => $modelDetail->product_id,
                    'is_flash_sale' => $modelDetail->is_flash_sale,
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
                ]
            ]
        ];
        $this->assertEquals($expect, $output);
    }
}
