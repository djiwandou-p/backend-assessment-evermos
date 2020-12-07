<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'qty' => $this->qty,
            'price' => $this->price,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'price_after_discount' => $this->price_after_discount,
            'product_id' => $this->id,
            'product' => new ProductResource($this->product)
        ];
    }
}
