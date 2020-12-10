<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\FlashSale;
use Tests\Feature\StoreTest;

class ProductTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public static $fieldValues = [
    	'name' => 'Janda Bolong',
        'sku' => 'JB001',
        'stock' => 100,
        'price' => 25000,
        'discount_type' => null,
        'discount' => null,
        'price_after_discount' => 25000,
    ];

    public static $fieldFlashSaleValues = [
    	'start_at' => null,
    	'end_at' => null,
    ];

    public function testGetAllProduct()
    {
        $model = StoreTest::__createData();
    	$response = $this->json('GET', '/api/v1/products');
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

        $modelProduct = self::__createData($model->id);
    	$response = $this->json('GET', '/api/v1/products');
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
						"id" => $modelProduct->id,
						"name" => $modelProduct->name,
						"sku" => $modelProduct->sku,
						"stock" => $modelProduct->stock,
						"price" => $modelProduct->price,
						"discount_type" => $modelProduct->discount_type,
						"discount" => $modelProduct->discount,
						"price_after_discount" => $modelProduct->price_after_discount,
						"store" => [
							"id" => $modelProduct->store->id,
							"name" => $modelProduct->store->name,
							"created_at" => $modelProduct->store->created_at,
							"updated_at" => $modelProduct->store->updated_at,
						]
					]
				]
	      	]
	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testAddNewProduct()
    {
        $model = StoreTest::__createData();
    	$response = $this->json('POST', '/api/v1/product');
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

        self::$fieldValues['store_id'] = $model->id;
    	$response = $this->json('POST', '/api/v1/product', self::$fieldValues, ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 201,
	          	"message" => "Created succesfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $response['content']['id'],
				"name" => self::$fieldValues['name'],
				"sku" => self::$fieldValues['sku'],
				"stock" => self::$fieldValues['stock'],
				"price" => self::$fieldValues['price'],
				"discount_type" => self::$fieldValues['discount_type'],
				"discount" => self::$fieldValues['discount'],
				"price_after_discount" => self::$fieldValues['price_after_discount']
			]

	  	];
        $response->assertStatus(201)->assertJson($expect);
    }

    public function testGetProductById()
    {
        $model = StoreTest::__createData();
    	$response = $this->json('GET', '/api/v1/product/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $modelProduct = self::__createData($model->id);
    	$response = $this->json('GET', '/api/v1/product/'.$modelProduct->id);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $modelProduct->id,
				"name" => $modelProduct->name,
				"sku" => $modelProduct->sku,
				"stock" => $modelProduct->stock,
				"price" => $modelProduct->price,
				"discount_type" => $modelProduct->discount_type,
				"discount" => $modelProduct->discount,
				"price_after_discount" => $modelProduct->price_after_discount
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testUpdateProductById()
    {
        $model = StoreTest::__createData();
    	$response = $this->json('PUT', '/api/v1/product/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $modelProduct = self::__createData($model->id);
        self::$fieldValues['name'] = 'My Product';
    	$response = $this->json('PUT', '/api/v1/product/'.$modelProduct->id, self::$fieldValues, ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "Updated successfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $modelProduct->id,
				"name" => 'My Product',
				"sku" => $modelProduct->sku,
				"stock" => $modelProduct->stock,
				"price" => $modelProduct->price,
				"discount_type" => $modelProduct->discount_type,
				"discount" => $modelProduct->discount,
				"price_after_discount" => $modelProduct->price_after_discount
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testAddProductIntoFlashSale()
    {
        $model = StoreTest::__createData();
    	$response = $this->json('POST', '/api/v1/product/asd/flash-sale');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $modelProduct = self::__createData($model->id);
        self::$fieldFlashSaleValues['start_at'] = date('Y-m-d H:i:s');
        self::$fieldFlashSaleValues['end_at'] = date('Y-m-d H:i:s', strtotime('+1 days'));
    	$response = $this->json('POST', '/api/v1/product/'.$modelProduct->id.'/flash-sale', array_merge(self::$fieldFlashSaleValues, self::$fieldValues), ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 201,
	          	"message" => "Created succesfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $modelProduct->id,
				"name" => $modelProduct->name,
				"sku" => $modelProduct->sku,
				"stock" => $modelProduct->stock,
				"price" => $modelProduct->price,
				"discount_type" => $modelProduct->discount_type,
				"discount" => $modelProduct->discount,
				"price_after_discount" => $modelProduct->price_after_discount,
				"flashSale" => [
			        'id' => $modelProduct->flashSale->id,
			        'start_at' => $modelProduct->flashSale->start_at,
			        'end_at' => $modelProduct->flashSale->end_at,
			        'stock' => $modelProduct->flashSale->stock,
			        'price' => $modelProduct->flashSale->price,
			        'discount_type' => $modelProduct->flashSale->discount_type,
			        'discount' => $modelProduct->flashSale->discount,
			        'price_after_discount' => $modelProduct->flashSale->price_after_discount,
			        'product_id' => $modelProduct->flashSale->product_id,
			        'created_at' => $modelProduct->flashSale->created_at,
			        'updated_at' => $modelProduct->flashSale->updated_at,
				]
	      	]

	  	];
        $response->assertStatus(201)->assertJson($expect);
    }

    public function testGetFlashSaleByProductId()
    {
        $model = StoreTest::__createData();
    	$response = $this->json('GET', '/api/v1/product/asd/flash-sale');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $modelProduct = self::__createData($model->id);
    	$response = $this->json('GET', '/api/v1/product/'.$modelProduct->id.'/flash-sale');
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $modelProduct->id,
				"name" => $modelProduct->name,
				"sku" => $modelProduct->sku,
				"stock" => $modelProduct->stock,
				"price" => $modelProduct->price,
				"discount_type" => $modelProduct->discount_type,
				"discount" => $modelProduct->discount,
				"price_after_discount" => $modelProduct->price_after_discount,
				"flashSales" => []
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);

        $modelFlashSale = self::__createDataFlashSale($modelProduct->id);
    	$response = $this->json('GET', '/api/v1/product/'.$modelProduct->id.'/flash-sale');
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $modelProduct->id,
				"name" => $modelProduct->name,
				"sku" => $modelProduct->sku,
				"stock" => $modelProduct->stock,
				"price" => $modelProduct->price,
				"discount_type" => $modelProduct->discount_type,
				"discount" => $modelProduct->discount,
				"price_after_discount" => $modelProduct->price_after_discount,
				"flashSales" => [
					[
				        'id' => $modelFlashSale->id,
				        'start_at' => $modelFlashSale->start_at,
				        'end_at' => $modelFlashSale->end_at,
				        'stock' => $modelFlashSale->stock,
				        'price' => $modelFlashSale->price,
				        'discount_type' => $modelFlashSale->discount_type,
				        'discount' => $modelFlashSale->discount,
				        'price_after_discount' => $modelFlashSale->price_after_discount,
				        'product_id' => $modelFlashSale->product_id,
				        'created_at' => $modelFlashSale->created_at,
				        'updated_at' => $modelFlashSale->updated_at,
				    ]
				]
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testDeleteProductById()
    {
        $model = StoreTest::__createData();
    	$response = $this->json('DELETE', '/api/v1/product/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $modelProduct = self::__createData($model->id);
    	$response = $this->json('DELETE', '/api/v1/product/'.$modelProduct->id);
        $response->assertStatus(204);
    }

    public static function __createData($store_id)
    {
    	$model = new Product;
    	$model->forceFill(self::$fieldValues);
    	$model->store_id = $store_id;
    	$model->save();
    	return $model;
    }

    public static function __createDataFlashSale($product_id)
    {
    	$model = new FlashSale;
    	$data = array_merge(self::$fieldFlashSaleValues, self::$fieldValues);
    	unset($data['name']);
    	unset($data['sku']);
    	unset($data['store_id']);
    	$model->forceFill($data);
    	$model->product_id = $product_id;
    	$model->save();
    	return $model;
    }
}
