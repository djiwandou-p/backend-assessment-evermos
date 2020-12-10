<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\PlayerTest;
use Tests\Feature\ContainerTest;
use App\Http\Resources\ContainerResource;

class ContainerResourceTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $player;

    public function setUp(): void {
        parent::setUp();
        $this->player = PlayerTest::__createData();
    }

    /**
     * An unit test to get player resource
     *
     * @return void
     */
    public function testGetContainerResource()
    {
        $resources = ContainerResource::collection([]);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [];
        $this->assertEquals($expect, $output);

        $model = ContainerTest::__createData($this->player->id);
        $resources = new ContainerResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
            'id' => $model->id,
            'name' => $model->name,
            'capacity' => $model->capacity,
            'ammount' => $model->ammount,
		];
        $this->assertEquals($expect, $output);
    }
}
