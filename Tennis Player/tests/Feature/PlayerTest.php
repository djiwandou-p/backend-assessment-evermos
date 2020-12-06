<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Player;
use Tests\Feature\ContainerTest;

class PlayerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public static $fieldValues = [
    	'name' => 'Rana Krisna',
    	'state' => 'NOT READY'
    ];

    public function testGetAllPlayer()
    {
    	$response = $this->json('GET', '/api/v1/players');
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
    	$response = $this->json('GET', '/api/v1/players');
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
						"state" => $model->state,
						"container" => [],
					]
				]
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);

        $modelContainer = ContainerTest::__createData($model->id);
    	$response = $this->json('GET', '/api/v1/players');
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
						"state" => $model->state,
						"container" => [
							[
								"id" => $modelContainer->id,
								"name" => $modelContainer->name,
								"capacity" => $modelContainer->capacity,
								"ammount" => $modelContainer->ammount
							]
						],
					]
				]
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testAddNewPlayer()
    {
    	$response = $this->json('POST', '/api/v1/player');
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

    	$response = $this->json('POST', '/api/v1/player', self::$fieldValues, ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 201,
	          	"message" => "Created succesfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $response['content']['id'],
				"name" => self::$fieldValues['name'],
				"state" => self::$fieldValues['state'],
				"container" => []
			]

	  	];
        $response->assertStatus(201)->assertJson($expect);
    }

    public function testGetPlayerById()
    {
    	$response = $this->json('GET', '/api/v1/player/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $model = self::__createData();
    	$response = $this->json('GET', '/api/v1/player/'.$model->id);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => $model->name,
				"state" => $model->state,
				"container" => [],
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);

        $modelContainer = ContainerTest::__createData($model->id);
    	$response = $this->json('GET', '/api/v1/player/'.$model->id);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "OK",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => $model->name,
				"state" => $model->state,
				"container" => [
					[
						"id" => $modelContainer->id,
						"name" => $modelContainer->name,
						"capacity" => $modelContainer->capacity,
						"ammount" => $modelContainer->ammount
					]
				],
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testUpdatePlayerById()
    {
    	$response = $this->json('PUT', '/api/v1/player/asdasd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $model = self::__createData();
        self::$fieldValues['name'] = 'Lisda Kania Yuliani';
    	$response = $this->json('PUT', '/api/v1/player/'.$model->id, self::$fieldValues, ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "Updated successfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => 'Lisda Kania Yuliani',
				"state" => $model->state,
				"container" => [],
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
        
        $modelContainer = ContainerTest::__createData($model->id);
    	$response = $this->json('PUT', '/api/v1/player/'.$model->id, self::$fieldValues, ['Accept' => 'application/json']);
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "Updated successfully.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => 'Lisda Kania Yuliani',
				"state" => $model->state,
				"container" => [
					[
						"id" => $modelContainer->id,
						"name" => $modelContainer->name,
						"capacity" => $modelContainer->capacity,
						"ammount" => $modelContainer->ammount
					]
				],
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testPlayPlayerById()
    {
    	$response = $this->json('PATCH', '/api/v1/player/asdasd/play');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $model = self::__createData();
    	$response = $this->json('PATCH', '/api/v1/player/'.$model->id.'/play');
    	$expect = [
	      	"header" => [
	          	"code" => 400,
	          	"message" => "You're not ready to play.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => $model->name,
				"state" => $model->state,
				"container" => [],
	      	]

	  	];
        $response->assertStatus(400)->assertJson($expect);

        $model->state = 'READY';
        $model->save();
    	$response = $this->json('PATCH', '/api/v1/player/'.$model->id.'/play');
    	$expect = [
	      	"header" => [
	          	"code" => 400,
	          	"message" => "You don't have any container",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => $model->name,
				"state" => $model->state,
				"container" => [],
	      	]

	  	];
        $response->assertStatus(400)->assertJson($expect);

        $modelContainer = ContainerTest::__createData($model->id);
        $modelContainer->ammount = $modelContainer->capacity;
        $modelContainer->save();
    	$response = $this->json('PATCH', '/api/v1/player/'.$model->id.'/play');
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "You're played.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => $model->name,
				"state" => 'PLAYED',
				"container" => [
					[
						"id" => $modelContainer->id,
						"name" => $modelContainer->name,
						"capacity" => $modelContainer->capacity,
						"ammount" => $modelContainer->ammount
					]
				],
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
        
    	$response = $this->json('PATCH', '/api/v1/player/'.$model->id.'/play');
    	$expect = [
	      	"header" => [
	          	"code" => 200,
	          	"message" => "You're in play.",
	          	"status" => true
	      	],
	      	"content" => [
				"id" => $model->id,
				"name" => $model->name,
				"state" => 'PLAYED',
				"container" => [
					[
						"id" => $modelContainer->id,
						"name" => $modelContainer->name,
						"capacity" => $modelContainer->capacity,
						"ammount" => $modelContainer->ammount
					]
				],
	      	]

	  	];
        $response->assertStatus(200)->assertJson($expect);
    }

    public function testDeletePlayerById()
    {
    	$response = $this->json('DELETE', '/api/v1/player/asd');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Data not found.",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);

        $model = self::__createData();
    	$response = $this->json('DELETE', '/api/v1/player/'.$model->id);
        $response->assertStatus(204);
    }

    public static function __createData()
    {
    	$model = new Player;
    	$model->forceFill(self::$fieldValues);
    	$model->save();
    	return $model;
    }
}
