<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery_type_list extends Model
{
    use HasFactory;

    protected $fillable = [
        'lebel',
        'status',   
    ];
}