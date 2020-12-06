<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Container;
use Tests\Feature\PlayerTest;

class ContainerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public static $fieldValues = [
    	'name' => 'Container 1',
    	'capacity' => 3,
    	'ammount' => 0,
    ];

    public function testGetAllContainer()
    {
        $model = PlayerTest::__createData();
    	$response = $this->json('GET', '/api/v1/player/'.$model->id.'/containers');
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

        $modelContainer = self::__createData($model->id);
    	$response = $this->json('GET', '/api/v1/player/'.$model->id.'/containers');
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
						"id" => $modelContainer->id,
						"name" => $modelContainer->name,
						"capacity" => $modelContainer->capacity,
						"ammount" => $modelContainer->ammount
					]
				]
	      	]
	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testAddNewContainer()
    {
        $model = PlayerTest::__createData();
    	$response = $this->json('POST', '/api/v1/player/'.$model->id.'/container');
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

    	$response = $this->json('POST', '/api/v1/player/'.$model->id.'/container', self::$fieldValues, ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 201,
	          	"message" => "Created succesfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $response['content']['id'],
				"name" => self::$fieldValues['name'],
				"capacity" => self::$fieldValues['capacity'],
				"ammount" => self::$fieldValues['ammount']
			]

	  	];
        $response->assertStatus(201)->assertJson($expect);
    }

    public function testGetContainerById()
    {
        $model = PlayerTest::__createData();
    	$response = $this->json('GET', '/api/v1/player/'.$model->id.'/container/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $modelContainer = self::__createData($model->id);
    	$response = $this->json('GET', '/api/v1/player/'.$model->id.'/container/'.$modelContainer->id);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $modelContainer->id,
				"name" => $modelContainer->name,
				"capacity" => $modelContainer->capacity,
				"ammount" => $modelContainer->ammount,
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testUpdateContainerById()
    {
        $model = PlayerTest::__createData();
    	$response = $this->json('PUT', '/api/v1/player/'.$model->id.'/container/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $modelContainer = self::__createData($model->id);
        self::$fieldValues['name'] = 'My Container';
    	$response = $this->json('PUT', '/api/v1/player/'.$model->id.'/container/'.$modelContainer->id, self::$fieldValues, ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "Updated successfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $modelContainer->id,
				"name" => 'My Container',
				"capacity" => $modelContainer->capacity,
				"ammount" => $modelContainer->ammount
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testPutBallIntoContainerById()
    {
        $model = PlayerTest::__createData();
    	$response = $this->json('PATCH', '/api/v1/player/'.$model->id.'/container/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $modelContainer = self::__createData($model->id);
        for ($i=1; $i <= self::$fieldValues['capacity'] ; $i++) { 
	    	$response = $this->json('PATCH', '/api/v1/player/'.$model->id.'/container/'.$modelContainer->id, self::$fieldValues, ['Accept' => 'application/json']);
	    	$expect = [
		      	"header" => [
		          	"code" => 200,
		          	"message" => "OK",
		          	"status" => true
		      	],
		      	"content" => [
					"id" => $modelContainer->id,
					"name" => $modelContainer->name,
					"capacity" => $modelContainer->capacity,
					"ammount" => $modelContainer->ammount+$i
		      	]

		  	];
	        $response->assertStatus(200)->assertJson($expect);
        }
        $modelContainer = Container::find($modelContainer->id);
    	$response = $this->json('PATCH', '/api/v1/player/'.$model->id.'/container/'.$modelContainer->id, self::$fieldValues, ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 403,
	          	"message" => "The Container is fully loaded with tennis balls and you're ready to play.",
	          	"status" => false
	      	],
	      	"content" => [
				"id" => $modelContainer->id,
				"name" => $modelContainer->name,
				"capacity" => $modelContainer->capacity,
				"ammount" => $modelContainer->ammount
	      	]

	  	];
        $response->assertStatus(403)->assertJson($expect);
    }

    public function testDeleteContainerById()
    {
        $model = PlayerTest::__createData();
    	$response = $this->json('DELETE', '/api/v1/player/'.$model->id.'/container/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $modelContainer = self::__createData($model->id);
    	$response = $this->json('DELETE', '/api/v1/player/'.$model->id.'/container/'.$modelContainer->id);
        $response->assertStatus(204);
    }

    public static function __createData($player_id)
    {
    	$model = new Container;
    	$model->forceFill(self::$fieldValues);
    	$model->player_id = $player_id;
    	$model->save();
    	return $model;
    }
}
