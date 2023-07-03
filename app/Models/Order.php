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
        'menu_id',
        'vender_price',
        'discount_per',
        'price_after_discount',
        'margin_per',
        'customer_price',
        'qty',
        'total_vender_amount',
        'total_customer_amount',
        'order_status',
        'pickup_address_id',
        'delivery_address_id',
        'payment_type',
        'payment_status',
        'payment_id',
        'delivery_type',
        'delivery_user_id',
    ];


    public function sales(){
        return $this->hasOne(Sale::class, 'id', 'sales_id');
    }
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function vender(){
        return $this->hasOne(User::class, 'id', 'vender_id');
    }

    public function menu(){
        return $this->hasOne(Food_menu::class, 'id', 'menu_id');
    }
    public function delivery(){
        return $this->hasOne(User_address_list::class, 'id', 'delivery_address_id');
    }

    public function pick_up(){
        return $this->hasOne(User_address_list::class, 'id', 'pickup_address_id');
    }
    /*
    public function default_image(){
        return $this->hasOne(Food_menu_image::class, 'id', 'menu_profile_img_id');
    }
    */


    public function images(){
        return $this->hasMany(Order::class, 'sales_id', 'id');
    }
}