<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food_delivery_list extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'address_id',
        'fetch_status',
        'fetch_date_time',
        'deliver_status',
        'deliver_date_time',
    ];
}