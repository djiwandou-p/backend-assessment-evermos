<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleResource extends JsonResource
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
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'stock' => $this->stock,
            'price' => $this->price,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'price_after_discount' => $this->price_after_discount,
            'product_id' => $this->product_id,
            'product' => new ProductResource($this->whenLoaded('product'))
        ];
    }
}
