<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food_menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'food_title',
        'food_description',
        'food_category',
        'food_category_id',
        'image_name',
        'veg_status',
        'halal_status',
        'rating',
        'vender_price',
        'customer_price ',
        'discount_per',
        'menu_profile_img_id',
        'active_status',
        'delete_status',
    ];
    

 

    public function images(){
        return $this->hasMany(Food_menu_image::class, 'food_menu_id', 'id');
    }

    public function halal_status_string(){
        // protected $strSlug=;
         return $this->hasOne(Select_list_option::class, 'integer_value', 'halal_status')->where('options_name','=', 'halal_status_lists');
     }


    public function default_image(){
        return $this->hasOne(Food_menu_image::class, 'id', 'menu_profile_img_id');
    }
    public function head(){
        return $this->hasMany(Food_menu_argument_heading::class, 'food_menu_id', 'id');
    }

    public function extra(){
        return $this->hasMany(Orders_menu_arrgument::class, 'menu_id', 'id');
    }



    public function venderInfo(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public function topCommnets(){
        return $this->hasMany(Food_comment::class, 'vender_id', 'user_id');
    }

    public function arguments(){
        return $this->hasMany(Food_menu_argument_heading::class, 'food_menu_id', 'id');
    }

}