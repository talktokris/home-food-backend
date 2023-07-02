<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home_page_slide extends Model
{
    use HasFactory;
            		
    protected $fillable = [
        'title',
        'image_name',
        'status',
    ];
}