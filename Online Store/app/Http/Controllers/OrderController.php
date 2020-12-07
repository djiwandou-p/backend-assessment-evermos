<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController as ApiController;

class OrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Order::get();
        return $this->sendResponse(["total" => $models->count(), "data" => OrderResource::collection($models)], 'OK');
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
            'email' => 'nullable|email|max:64',
            'delivery_to' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), 'Validation Error', 400, false);
        }
        $model = Order::create($data);
        return $this->sendResponse(new OrderResource($model), 'Created succesfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $this->sendResponse(new OrderResource($order), 'OK');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order->update($request->all());
        return $this->sendResponse(new OrderResource($order), 'Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return $this->sendResponse(new OrderResource($order), 'Deleted successfully.', 204);
    }
}
