<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'user_id',
        'vender_id',
        'food_id',
        'sold_price',
        'order_status',
        'payment_type',
        'payment_status',
        'payment_id',
        'delivery_type',
        'delivery_user_id',
    ];
}