<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food_comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_name',
        'vender_id',
        'food_id',
        'comments',
        'rating',
        'comment_status',

    ];



    // public function authors(){
    //     return $this->hasOne(User::class, 'id', 'user_id');
    // }


    public function author(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}