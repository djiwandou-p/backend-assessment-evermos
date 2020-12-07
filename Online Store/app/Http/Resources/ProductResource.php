<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StoreResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'price' => $this->price,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'price_after_discount' => $this->price_after_discount,
            'store' => $this->whenLoaded('store'),
            'flashSale' => $this->whenLoaded('flashSale'),
            'flashSales' => $this->whenLoaded('flashSales')
        ];
    }
}
