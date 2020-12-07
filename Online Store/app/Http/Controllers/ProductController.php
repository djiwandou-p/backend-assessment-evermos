<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Product::with('containers')->get();
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
        $player->update($request->all());
        return $this->sendResponse(new ProductResource($product), 'Updated successfully.');
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
        return $this->sendResponse(new PlayerResource($player), 'Deleted successfully.', 204);
    }
}
