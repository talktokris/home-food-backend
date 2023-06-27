<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food_comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vender_id',
        'food_id',
        'comments',
        'comment_status',

    ];
}