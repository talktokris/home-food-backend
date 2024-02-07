<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food_menu_argument_heading extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'pick_type',
        'status',
        'delete_status',
    ];


    public function argumentList(){
        return $this->hasMany(Food_menu_argument_item::class, 'heading_id', 'id');
    }


}