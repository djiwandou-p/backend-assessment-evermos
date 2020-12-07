<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Store;
use Tests\Feature\ProductTest;

class StoreTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public static $fieldValues = [
    	'name' => "Alkana's House",
    ];

    public function testGetAllStore()
    {
    	$response = $this->json('GET', '/api/v1/stores');
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

        $model = self::__createData();
    	$response = $this->json('GET', '/api/v1/stores');
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
						"id" => $model->id,
						"name" => $model->name,
						"products" => [],
					]
				]
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);

        $modelProduct = ProductTest::__createData($model->id);
    	$response = $this->json('GET', '/api/v1/stores');
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
						"id" => $model->id,
						"name" => $model->name,
						"products" => [
							[
								"id" => $modelProduct->id,
								"name" => $modelProduct->name,
								"sku" => $modelProduct->sku,
								"stock" => $modelProduct->stock,
								"price" => $modelProduct->price,
								"discount_type" => $modelProduct->discount_type,
								"discount" => $modelProduct->discount,
								"price_after_discount" => $modelProduct->price_after_discount
							]
						],
					]
				]
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testAddNewStore()
    {
    	$response = $this->json('POST', '/api/v1/store');
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

    	$response = $this->json('POST', '/api/v1/store', self::$fieldValues, ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 201,
	          	"message" => "Created succesfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $response['content']['id'],
				"name" => self::$fieldValues['name'],
			]

	  	];
        $response->assertStatus(201)->assertJson($expect);
    }

    public function testGetStoreById()
    {
    	$response = $this->json('GET', '/api/v1/store/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $model = self::__createData();
    	$response = $this->json('GET', '/api/v1/store/'.$model->id);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => $model->name,
				"products" => [],
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);

        $modelProduct = ProductTest::__createData($model->id);
    	$response = $this->json('GET', '/api/v1/store/'.$model->id);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => $model->name,
				"products" => [
					[
						"id" => $modelProduct->id,
						"name" => $modelProduct->name,
						"sku" => $modelProduct->sku,
						"stock" => $modelProduct->stock,
						"price" => $modelProduct->price,
						"discount_type" => $modelProduct->discount_type,
						"discount" => $modelProduct->discount,
						"price_after_discount" => $modelProduct->price_after_discount
					]
				],
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testUpdateStoreById()
    {
    	$response = $this->json('PUT', '/api/v1/store/asdasd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $model = self::__createData();
        self::$fieldValues['name'] = 'Rumah Kita';
    	$response = $this->json('PUT', '/api/v1/store/'.$model->id, self::$fieldValues, ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "Updated successfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => 'Rumah Kita',
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testDeleteStoreById()
    {
    	$response = $this->json('DELETE', '/api/v1/store/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $model = self::__createData();
    	$response = $this->json('DELETE', '/api/v1/store/'.$model->id);
        $response->assertStatus(204);
    }

    public function testGetProductStoreById()
    {
    	$response = $this->json('GET', '/api/v1/store/asd/products');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $model = self::__createData();
    	$response = $this->json('GET', '/api/v1/store/'.$model->id);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => $model->name,
				"products" => [],
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);

        $modelProduct = ProductTest::__createData($model->id);
    	$response = $this->json('GET', '/api/v1/store/'.$model->id.'/products');
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
					]
				]
	      	]
	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public static function __createData()
    {
    	$model = new Store;
    	$model->forceFill(self::$fieldValues);
    	$model->save();
    	return $model;
    }
}
