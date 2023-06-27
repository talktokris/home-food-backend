<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;


    protected $fillable = [
        'lebel',
        'zip_code',
        'status'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}