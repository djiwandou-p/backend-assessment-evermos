<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\StoreTest;
use Tests\Feature\ProductTest;
use App\Http\Resources\FlashSaleResource;

class FlashSaleResourceTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $store;
    private $product;

	public function setUp(): void {
		parent::setUp();
		$this->store = StoreTest::__createData();
		$this->product = ProductTest::__createData($this->store->id);
	}


    /**
     * An unit test to get flash sale resource
     *
     * @return void
     */
    public function testGetFlashSaleResource()
    {
        $resources = FlashSaleResource::collection([]);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [];
        $this->assertEquals($expect, $output);

        $model = ProductTest::__createDataFlashSale($this->product->id);
        $resources = new FlashSaleResource($model);
        $output = $resources->response()->getData(true);
        $expect = [];
        $expect['data'] = [
	        'id' => $model->id,
	        'start_at' => $model->start_at,
	        'end_at' => $model->end_at,
	        'stock' => $model->stock,
	        'price' => $model->price,
	        'discount_type' => $model->discount_type,
	        'discount' => $model->discount,
	        'price_after_discount' => $model->price_after_discount,
	        'product_id' => $model->product_id,
	        'created_at' => $model->created_at,
	        'updated_at' => $model->updated_at
		];
        $this->assertEquals($expect, $output);
    }
}
