<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Select_list_option extends Model
{
    use HasFactory;


    protected $fillable = [
        'options_name',
        'sting_value',
        'integer_value',
        'status',
       
    ];
}