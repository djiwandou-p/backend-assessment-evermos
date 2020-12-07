<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController as ApiController;
use App\Http\Resources\ProductResource;

class StoreController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Store::with('products')->get();
        return $this->sendResponse(["total" => $models->count(), "data" => StoreResource::collection($models)], 'OK');
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
        $model = Store::create($data);
        return $this->sendResponse(new StoreResource($model), 'Created succesfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        $store->load('products');
        return $this->sendResponse(new StoreResource($store), 'OK');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function showProducts(Store $store)
    {
        $store->load('products');
        return $this->sendResponse(["total" => $store->products->count(), "data" => ProductResource::collection($store->products)], 'OK');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $store->update($request->all());
        return $this->sendResponse(new StoreResource($store), 'Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $store->delete();
        return $this->sendResponse(new StoreResource($store), 'Deleted successfully.', 204);
    }
}
