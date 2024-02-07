<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;



    protected $fillable = [
        'user_id',
        'vender_id',
        'total_items',
        'vender_amount',
        'margin_amount',
        'deliver_fee',
        'tax',
        'customer_amount',
        'grand_total',
        'payment_type',
        'payment_status',
        'payment_id',
        'deliver_address_id',
        'deliver_status',
        'order_status',
        'status',
    ];


    public function orders(){
        return $this->hasMany(Order::class, 'sales_id', 'id');
    }

    public function users(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function vender(){
        return $this->hasOne(User::class, 'id', 'vender_id');
    }
    public function allStatus(){
        // protected $strSlug=;
         return $this->hasOne(Select_list_option::class, 'integer_value', 'order_status')->where('options_name','=', 'order_status_lists');
     }


}