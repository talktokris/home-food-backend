<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'otp',
        'device_name',
        'role_id',
        'address_id',
        'search_radius',
        'app_margin_per',
        'country_id',
        'mobile_no',
    ];

    public function country(){
       return $this->hasOne(Country::class, 'id', 'country_id');
      // return $this->hasOne(Country::class, 'foreign_key', 'local_key');
    }
    public function role(){
        return $this->hasOne(Users_role::class, 'id', 'role_id');
    }

    public function address(){
        return $this->hasOne(Food_menu_image::class, 'id', 'address_id');
    }
    public function address_list(){
        return $this->hasMany(User_address_list::class, 'user_id', 'id');
    }



    


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}