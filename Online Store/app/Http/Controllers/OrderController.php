<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
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
        $models = Order::with(['orderDetails', 'orderDetails.product'])->get();
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
        $message = [
            'products.*.product_id.distinct' => 'Product has a duplicate value.',
            'products.*.product_id.exists' => 'Product not found.',
        ];
        $validator = Validator::make($data, [
            'name' => 'required|max:191',
            'email' => 'nullable|email|max:64',
            'delivery_to' => 'required',
            "products"    => "required|array|min:1",
            "products.*.product_id"  => "required|int|distinct|exists:products,id",
            "products.*.is_flash_sale"  => "required|boolean|checkFlashSale",
            "products.*.qty"  => "required|int|min:1|checkQty",
        ], $message);
        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), 'Validation Error', 400, false);
        }
        $model = \DB::transaction(function () use ($data) {
            $model = Order::create($data);
            $dataDetail = array_map(function($item) use($model){
                $product = Product::with(['flashSale'])->find($item['product_id']);
                $data['order_id'] = $model->id;
                $data['is_flash_sale'] = $item['is_flash_sale'];
                $data['qty'] = $item['qty'];
                $data['price'] = $product->price;
                $data['discount_type'] = $product->discount_type;
                $data['discount'] = $product->discount;
                $data['price_after_discount'] = $product->price_after_discount;
                $data['product_id'] = $product->id;
                if($data['is_flash_sale'] == true && !empty($product->flashSale)){
                    $data['price'] = $product->flashSale->price;
                    $data['discount_type'] = $product->flashSale->discount_type;
                    $data['discount'] = $product->flashSale->discount;
                    $data['price_after_discount'] = $product->flashSale->price_after_discount;
                }
                return $data;
            }, $data['products']);
            $modelDetail = OrderDetail::insert($dataDetail);


            $dataDetails = OrderDetail::with('product')->where('order_id', $model->id)->get();
            foreach ($dataDetails as $key => $detail) {
                Product::decreaseStock($detail->product, $detail->qty);
            }
            $model->load(['orderDetails.product', 'orderDetails.product.flashSale']);
            return $model;
        });
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
        $order->load(['orderDetails', 'orderDetails.product']);
        return $this->sendResponse(new OrderResource($order), 'OK');
    }
}
