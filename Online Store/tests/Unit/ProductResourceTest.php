<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\StoreTest;
use Tests\Feature\ProductTest;
use App\Http\Resources\ProductResource;

class ProductResourceTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $store;

	public function setUp(): void {
		parent::setUp();
		$this->store = StoreTest::__createData();
	}


    /**
     * An unit test to get product resource
     *
     * @return void
     */
    public function testGetProductResource()
    {
        $resources = ProductResource::collection([]);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [];
        $this->assertEquals($expect, $output);

        $model = ProductTest::__createData($this->store->id);
        $resources = new ProductResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
			"sku" => $model->sku,
			"stock" => $model->stock,
			"price" => $model->price,
			"discount_type" => $model->discount_type,
			"discount" => $model->discount,
			"price_after_discount" => $model->price_after_discount
		];
        $this->assertEquals($expect, $output);

        $model->unsetRelation('store', 'flashSale', 'flashSales');
        $model->load('store');
        $resources = new ProductResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
			"sku" => $model->sku,
			"stock" => $model->stock,
			"price" => $model->price,
			"discount_type" => $model->discount_type,
			"discount" => $model->discount,
			"price_after_discount" => $model->price_after_discount,
			"store" => [
				"id" => $model->store->id,
				"name" => $model->store->name,
				"created_at" => $model->store->created_at,
				"updated_at" => $model->store->updated_at,
			]
		];
        $this->assertEquals($expect, $output);

        $model->unsetRelation('store', 'flashSale', 'flashSales');
        $model->load(['flashSale', 'flashSales']);
        $resources = new ProductResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
			"sku" => $model->sku,
			"stock" => $model->stock,
			"price" => $model->price,
			"discount_type" => $model->discount_type,
			"discount" => $model->discount,
			"price_after_discount" => $model->price_after_discount,
			"flashSale" => null,
			"flashSales" => []
		];
        $this->assertEquals($expect, $output);

        $modelFlashSale = ProductTest::__createDataFlashSale($model->id);
        $model->unsetRelation('store', 'flashSale', 'flashSales');
        $model->load(['flashSale', 'flashSales']);
        $resources = new ProductResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
			"sku" => $model->sku,
			"stock" => $model->stock,
			"price" => $model->price,
			"discount_type" => $model->discount_type,
			"discount" => $model->discount,
			"price_after_discount" => $model->price_after_discount,
			"flashSale" => [
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
			],
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
		];
        $this->assertEquals($expect, $output);
    }

    /**
     * An unit test to get product resource with store
     *
     * @return void
     */
    public function testGetProductResourceWithStore()
    {
        $model = ProductTest::__createData($this->store->id);
        $model->load('store');
        $resources = new ProductResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
			"sku" => $model->sku,
			"stock" => $model->stock,
			"price" => $model->price,
			"discount_type" => $model->discount_type,
			"discount" => $model->discount,
			"price_after_discount" => $model->price_after_discount,
			"store" => [
				"id" => $model->store->id,
				"name" => $model->store->name,
				"created_at" => $model->store->created_at,
				"updated_at" => $model->store->updated_at,
			]
		];
        $this->assertEquals($expect, $output);
    }

    /**
     * An unit test to get product resource with flash sale
     *
     * @return void
     */
    public function testGetProductResourceWithFlashSale()
    {
        $model = ProductTest::__createData($this->store->id);
        $model->load(['flashSale', 'flashSales']);
        $resources = new ProductResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
			"sku" => $model->sku,
			"stock" => $model->stock,
			"price" => $model->price,
			"discount_type" => $model->discount_type,
			"discount" => $model->discount,
			"price_after_discount" => $model->price_after_discount,
			"flashSale" => null,
			"flashSales" => []
		];
        $this->assertEquals($expect, $output);

        $modelFlashSale = ProductTest::__createDataFlashSale($model->id);
        $model->load(['flashSale', 'flashSales']);
        $resources = new ProductResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
			"id" => $model->id,
			"name" => $model->name,
			"sku" => $model->sku,
			"stock" => $model->stock,
			"price" => $model->price,
			"discount_type" => $model->discount_type,
			"discount" => $model->discount,
			"price_after_discount" => $model->price_after_discount,
			"flashSale" => [
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
			],
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
		];
        $this->assertEquals($expect, $output);
    }
}
