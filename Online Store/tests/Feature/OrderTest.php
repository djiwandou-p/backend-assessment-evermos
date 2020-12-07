<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderDetail;
use Tests\Feature\StoreTest;
use Tests\Feature\ProductTest;

class OrderTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $store;
    private $product;

	public function setUp(): void {
		parent::setUp();
		$this->store = StoreTest::__createData();
		$this->product = ProductTest::__createData($this->store->id);

		self::$fieldValuesDetail['qty'] = $this->product->stock;
		self::$fieldValuesDetail['price'] = $this->product->price;
		self::$fieldValuesDetail['discount_type'] = $this->product->discount_type;
		self::$fieldValuesDetail['discount'] = $this->product->discount;
		self::$fieldValuesDetail['price_after_discount'] = $this->product->price_after_discount;
	}

    public static $fieldValues = [
    	'name' => 'Rana Krisna',
        'email' => 'ranakrisna17031995@gmail.com',
        'delivery_to' => 'Bandung',
        'status' => null
    ];

    public static $fieldValuesDetail = [
        'order_id' => null,
        'qty' => null,
        'price' => null,
        'discount_type' => null,
        'discount' => null,
        'price_after_discount' => null,
        'product_id' => null,
        'is_flash_sale' => false
    ];

    public function testGetAllOrder()
    {
    	$response = $this->json('GET', '/api/v1/orders');
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"total" => 0,
				"data" => []
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);

        $modelOrder = self::__createData();
    	$response = $this->json('GET', '/api/v1/orders');
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"total" => 1,
				"data" => [
					[
						"id" => $modelOrder->id,
						"name" => $modelOrder->name,
						"email" => $modelOrder->email,
						"delivery_to" => $modelOrder->delivery_to,
						"status" => $modelOrder->status,
						"details" => []
					]
				]
	      	]
	  	];
        $response->assertStatus(200)->assertJson($expect);

        $modelOrderDetail = self::__createDataDetail($modelOrder->id, $this->product->id);
    	$response = $this->json('GET', '/api/v1/orders');
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"total" => 1,
				"data" => [
					[
						"id" => $modelOrder->id,
						"name" => $modelOrder->name,
						"email" => $modelOrder->email,
						"delivery_to" => $modelOrder->delivery_to,
						"status" => $modelOrder->status,
						"details" => [
							[
						        'id' => $modelOrderDetail->id,
						        'order_id' => $modelOrderDetail->order_id,
						        'qty' => $modelOrderDetail->qty,
						        'price' => $modelOrderDetail->price,
						        'discount_type' => $modelOrderDetail->discount_type,
						        'discount' => $modelOrderDetail->discount,
						        'price_after_discount' => $modelOrderDetail->price_after_discount,
						        'product_id' => $modelOrderDetail->product_id,
						        'is_flash_sale' => $modelOrderDetail->is_flash_sale,
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
					]
				]
	      	]
	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testAddNewOrder()
    {
    	$response = $this->json('POST', '/api/v1/order');
    	$expect = [
	      	"header" => [
	          	"code" => 400,
	          	"message" => "Validation Error",
	          	"status" => false
	      	],
	      	"content" => [
	      		"name" => [
	      			"The name field is required."
	      		]
	      	]
	  	];
        $response->assertStatus(400)->assertJson($expect);

        $data = self::$fieldValues;
        $data['products'] = [];
        $data['products'][0]['product_id'] = $this->product->id;
        $data['products'][0]['is_flash_sale'] = false;
        $data['products'][0]['qty'] = $this->product->stock;
    	$response = $this->json('POST', '/api/v1/order', $data, ['Accept' => 'application/json']);

    	$modelOrder = Order::with('orderDetails')->find($response['content']['id']);
    	$expect = [
	      	"header" => [
	          	"code" => 201,
	          	"message" => "Created succesfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $modelOrder->id,
				"name" => $modelOrder->name,
				"email" => $modelOrder->email,
				"delivery_to" => $modelOrder->delivery_to,
				"status" => $modelOrder->status,
				"details" => [
					[
						"id" => $modelOrder->orderDetails[0]->id,
				        'order_id' => $modelOrder->orderDetails[0]->order_id,
				        'qty' => $modelOrder->orderDetails[0]->qty,
				        'price' => $modelOrder->orderDetails[0]->price,
				        'discount_type' => $modelOrder->orderDetails[0]->discount_type,
				        'discount' => $modelOrder->orderDetails[0]->discount,
				        'price_after_discount' => $modelOrder->orderDetails[0]->price_after_discount,
				        'is_flash_sale' => $modelOrder->orderDetails[0]->is_flash_sale,
				        'product_id' => $modelOrder->orderDetails[0]->product_id,
				        'product' => [
					        'id' => $this->product->id,
					        'name' => $this->product->name,
					        'sku' => $this->product->sku,
					        'stock' => 0,
					        'price' => $this->product->price,
					        'discount_type' => $this->product->discount_type,
					        'discount' => $this->product->discount,
					        'price_after_discount' => $this->product->price_after_discount,
					        'flashSale' => null
				        ]
					]
				]
			]

	  	];
        $response->assertStatus(201)->assertJson($expect);
    }

    public function testGetProductById()
    {
        $model = StoreTest::__createData();
    	$response = $this->json('GET', '/api/v1/order/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $modelOrder = self::__createData();
        $modelOrderDetail = self::__createDataDetail($modelOrder->id, $this->product->id);
    	$response = $this->json('GET', '/api/v1/order/'.$modelOrder->id);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $modelOrder->id,
				"name" => $modelOrder->name,
				"email" => $modelOrder->email,
				"delivery_to" => $modelOrder->delivery_to,
				"status" => $modelOrder->status,
				"details" => [
					[
						"id" => $modelOrder->orderDetails[0]->id,
				        'order_id' => $modelOrder->orderDetails[0]->order_id,
				        'qty' => $modelOrder->orderDetails[0]->qty,
				        'price' => $modelOrder->orderDetails[0]->price,
				        'discount_type' => $modelOrder->orderDetails[0]->discount_type,
				        'discount' => $modelOrder->orderDetails[0]->discount,
				        'price_after_discount' => $modelOrder->orderDetails[0]->price_after_discount,
				        'is_flash_sale' => $modelOrder->orderDetails[0]->is_flash_sale,
				        'product_id' => $modelOrder->orderDetails[0]->product_id,
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
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public static function __createData()
    {
    	$model = new Order;
    	$model->forceFill(self::$fieldValues);
    	$model->save();
    	return $model;
    }

    public static function __createDataDetail($orderId, $productId)
    {
    	$model = new OrderDetail;
    	$model->forceFill(self::$fieldValuesDetail);
    	$model->order_id = $orderId;
    	$model->product_id = $productId;
    	$model->save();
    	return $model;
    }
}
