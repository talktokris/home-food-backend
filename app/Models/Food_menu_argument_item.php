<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food_menu_argument_item extends Model
{
    use HasFactory;


    protected $fillable = [
        'food_menu_id',
        'heading_id',
        'description',
        'vender_price',
        'price',
        'status',
        'delete_status',
    ];

    public function heading(){
        return $this->hasOne(Food_menu_argument_heading::class, 'id', 'heading_id');
    }
}