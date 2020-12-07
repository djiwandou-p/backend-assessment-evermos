<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\FlashSale;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController as ApiController;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Product::with('store')->get();
        return $this->sendResponse(["total" => $models->count(), "data" => ProductResource::collection($models)], 'OK');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:191',
            'sku' => 'required|max:191|unique:products,sku',
            'stock' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
            'discount_type' => 'nullable|required_with:discount|in:PRICE,PERCENT',
            'discount' => 'required_with:discount_type,PRICE|required_with:discount_type,PERCENT|checkPriceAfterDiscount:0',
            'store_id' => 'required|exists:stores,id',
        ]);
        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), 'Validation Error', 400, false);
        }
        $model = Product::create($data);
        return $this->sendResponse(new ProductResource($model), 'Created succesfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load('store');
        return $this->sendResponse(new ProductResource($product), 'OK');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:191',
            'sku' => 'required|max:191|unique:products,sku,'.$product->id,
            'stock' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
            'discount_type' => 'nullable|required_with:discount|in:PRICE,PERCENT',
            'discount' => 'required_with:discount_type,PRICE|required_with:discount_type,PERCENT|checkPriceAfterDiscount:0',
            'store_id' => 'required|exists:stores,id',
        ]);
        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), 'Validation Error', 400, false);
        }
        if(!isset($data['discount_type']) || !isset($data['discount_type'])){
            $data['discount_type'] = null;
            $data['discount'] = null;
        }
        $product->update($data);
        return $this->sendResponse(new ProductResource($product), 'Updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function showFlashSale(Product $product)
    {
        $product->load('flashSales');
        return $this->sendResponse(new ProductResource($product), 'OK');
    }

    /**
     * Store the specified resource in flash shale storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function storeFlashSale(Request $request, Product $product)
    {
        $data = $request->all();
        $data['product_id'] = $product->id;
        if(!isset($data['stock']) || is_null($data['stock'])){
            $data['stock'] = $product->stock;
        }
        if(!isset($data['price']) || is_null($data['price'])){
            $data['price'] = $product->price;
        }
        if(!isset($data['discount_type']) || is_null($data['discount_type'])){
            $data['discount_type'] = $product->discount_type;
        }
        if(!isset($data['discount']) || is_null($data['discount'])){
            $data['discount'] = $product->discount;
        }

        $validator = Validator::make($data, [
            'start_at' => 'required',
            'end_at' => 'required',
            'stock' => 'nullable|integer|min:0|max:'.$product->stock,
            'price' => 'nullable|integer|min:0|max:'.$product->price,
            'discount_type' => 'nullable|required_with:discount|in:PRICE,PERCENT',
            'discount' => 'required_with:discount_type,PRICE|required_with:discount_type,PERCENT|checkPriceAfterDiscount:0',
        ]);
        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), 'Validation Error', 400, false);
        }
        $model = FlashSale::create($data);

        $product->load('flashSale');
        return $this->sendResponse(new ProductResource($product), 'Created succesfully.', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $player->delete();
        return $this->sendResponse(new ProductResource($player), 'Deleted successfully.', 204);
    }
}
