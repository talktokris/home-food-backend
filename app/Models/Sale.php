<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;



    protected $fillable = [
        'user_id',
        'total_items',
        'vender_amount',
        'margin_amount',
        'customer_amount',
        'payment_type',
        'payment_status',
        'payment_id',
        'status',
    ];


    public function orders(){
        return $this->hasMany(Order::class, 'sales_id', 'id');
    }

}