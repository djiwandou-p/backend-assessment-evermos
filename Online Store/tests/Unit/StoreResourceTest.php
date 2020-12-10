<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\StoreTest;
use Tests\Feature\ProductTest;
use App\Http\Resources\StoreResource;

class StoreResourceTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * An unit test to get store resource
     *
     * @return void
     */
    public function testGetStoreResource()
    {
        $resources = StoreResource::collection([]);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [];
        $this->assertEquals($expect, $output);


        $model = StoreTest::__createData();
        $resources = new StoreResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
		];
        $this->assertEquals($expect, $output);
    }

    /**
     * An unit test to get store resource with product
     *
     * @return void
     */
    public function testGetStoreResourceWithProduct()
    {
        $model = StoreTest::__createData();
        $model->load('products');
        $resources = new StoreResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
			"products" => [],
		];
        $this->assertEquals($expect, $output);

        $modelProduct = ProductTest::__createData($model->id);
        $model->load('products');
        $resources = new StoreResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
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
		];
        $this->assertEquals($expect, $output);
    }
}
