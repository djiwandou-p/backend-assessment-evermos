<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'name',
        'sku',
        'stock',
        'price',
        'discount_type',
        'discount',
        'price_after_discount',
        'store_id',
    ];

    protected $casts = [
        'name' => 'string',
        'sku' => 'string',
        'stock' => 'int',
        'price' => 'float',
        'discount_type' => 'string',
        'discount' => 'float',
        'price_after_discount' => 'float',
        'store_id' => 'int',
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            self::setPriceAfterDiscount($model);
        });
    }

    public static function setPriceAfterDiscount($model)
    {
        $price_after_discount = $model->price;
        if(!empty($model->discount) && !empty($model->discount_type)){
            switch ($model->discount_type) {
                case 'PRICE':
                    $price_after_discount = ($model->price - $model->discount);
                    break;
                case 'PERCENT':
                    $price_after_discount = $model->price - (($model->price * $model->discount) / 100);
                    break;
                default:
                    $price_after_discount = null;
                    break;
            }
        }
        $model->price_after_discount = $price_after_discount;
        return $model;
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class, 'id', 'product_id')->where(function($query){
            $query->where('start_at', '>', now());
            $query->where('end_at', '>', now());
        })->orderBy('created_at')->limit(1);
    }

    public function flashSales()
    {

        return $this->hasMany(FlashSale::class, 'product_id')->where(function($query){
            $query->where('start_at', '>', now());
            $query->where('end_at', '>', now());
        })->orderBy('created_at');
    }

    public function setDiscountTypeAttribute($value)
    {
        $data = null;
        if (!empty($value)) {
            $data = $value;
        }
        $this->attributes['discount_type'] = $data;
    }

    public function setDiscountAttribute($value)
    {
        $data = null;
        if (!empty($value)) {
            $data = $value;
        }
        $this->attributes['discount'] = $data;
    }

    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        } else {
            return date('Y-m-d H:i:s', strtotime($value));
        }
    }

    public function getUpdatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        } else {
            return date('Y-m-d H:i:s', strtotime($value));
        }
    }
}
