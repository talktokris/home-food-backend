<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_status_list extends Model
{
    use HasFactory;

    protected $fillable = [
        'lebel',
        'status',   
    ];
}