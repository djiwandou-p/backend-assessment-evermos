<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class FlashSale extends Model
{
    use HasFactory;
    protected $table = 'flash_sales';
    protected $hidden = ['laravel_through_key'];

    protected $fillable = [
        'start_at',
        'end_at',
        'stock',
        'price',
        'discount_type',
        'discount',
        'price_after_discount',
        'product_id',
    ];

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'string',
        'stock' => 'int',
        'price' => 'float',
        'discount_type' => 'string',
        'discount' => 'float',
        'price_after_discount' => 'float',
        'product_id' => 'int',
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            Product::setPriceAfterDiscount($model);
        });
    }

    public function setStartAtAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['start_at'] = date('Y-m-d H:i:s');
        } else {
            $this->attributes['start_at'] = date('Y-m-d H:i:s', strtotime($value));
        }
    }

    public function setEndAtAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['end_at'] = date('Y-m-d H:i:s');
        } else {
            $this->attributes['end_at'] = date('Y-m-d H:i:s', strtotime($value));
        }
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

    public function getStartAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        } else {
            return date('Y-m-d H:i:s', strtotime($value));
        }
    }

    public function getEndAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        } else {
            return date('Y-m-d H:i:s', strtotime($value));
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
