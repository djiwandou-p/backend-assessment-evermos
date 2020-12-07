<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'qty',
        'price',
        'discount_type',
        'discount',
        'price_after_discount',
        'product_id',
        'is_flash_sale',
    ];

    protected $casts = [
        'order_id' => 'int',
        'qty' => 'int',
        'price' => 'float',
        'discount_type' => 'string',
        'discount' => 'float',
        'price_after_discount' => 'float',
        'product_id' => 'int',
        'is_flash_sale' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            Product::setPriceAfterDiscount($model);
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
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
