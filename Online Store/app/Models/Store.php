<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $table = 'stores';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'string',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id');
    }

    public function flashSales()
    {
        return $this->hasManyThrough(FlashSale::class, Product::class, 'store_id', 'product_id', 'id', 'id')->where(function($query){
            $query->where('start_at', '<=', date('Y-m-d H:i:s'));
            $query->where('end_at', '>=', date('Y-m-d H:i:s'));
        });
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
