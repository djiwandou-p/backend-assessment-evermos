<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\PlayerTest;
use Tests\Feature\ContainerTest;
use App\Http\Resources\PlayerResource;

class PlayerResourceTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * An unit test to get player resource
     *
     * @return void
     */
    public function testGetPlayerResource()
    {
        $resources = PlayerResource::collection([]);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [];
        $this->assertEquals($expect, $output);


        $model = PlayerTest::__createData();
        $resources = new PlayerResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
            "state" => 'NOT READY',
            "containers" => []
		];
        $this->assertEquals($expect, $output);
    }

    /**
     * An unit test to get player resource with container
     *
     * @return void
     */
    public function testGetPlayerResourceWithContainer()
    {
        $model = PlayerTest::__createData();
        $model->load('containers');
        $resources = new PlayerResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
            "state" => 'NOT READY',
			"containers" => [],
		];
        $this->assertEquals($expect, $output);

        $modelContainer = ContainerTest::__createData($model->id);
        $model->load('containers');
        $resources = new PlayerResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
            "state" => 'NOT READY',
			"containers" => [
				[
                    'id' => $modelContainer->id,
                    'name' => $modelContainer->name,
                    'capacity' => $modelContainer->capacity,
                    'ammount' => $modelContainer->ammount,
				]
			],
		];
        $this->assertEquals($expect, $output);
    }
}
