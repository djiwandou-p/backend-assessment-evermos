<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'name',
        'email',
        'delivery_to',
        'status'
    ];

    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'delivery_to' => 'string',
        'status' => 'string'
    ];

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
