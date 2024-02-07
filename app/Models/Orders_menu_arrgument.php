<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders_menu_arrgument extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'menu_id',
        'order_id',
        'user_id',
        'vender_id',
        'argument_item_id',
        'vender_price',
        'customer_price',
        'discount',
        'status',
    
    ];


    public function extraInfo(){
        return $this->hasOne(Food_menu_argument_item::class, 'id', 'argument_item_id');
    }
}