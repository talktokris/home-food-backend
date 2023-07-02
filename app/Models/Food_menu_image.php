<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food_menu_image extends Model
{
    use HasFactory;


    protected $fillable = [
        'food_menu_id',
        'image_name',
    ];




    public function getUserInfo(){

        return $this->belongsTo(User::class, 'user_id', 'id');

    }

    public function getMenuInfo(){

        return $this->belongsTo(Food_menu::class, 'id', 'food_menu_id');

    }


}