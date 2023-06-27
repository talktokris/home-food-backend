<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class User_address_list extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'address',
        'street',
        'city_name',
        'state',
        'postal_code',
        'default_status',
        'status',

    ];
}