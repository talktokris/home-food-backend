<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       // return parent::toArray($request);


       return [
        'id' => $this->id,
        'name' => $this->name,
        'email'=>$this->email,
        'device_name'=>$this->device_name,
        'app_margin_per'=>$this->app_margin_per,
        'role'=> new RoleResource($this->role),
        'country'=> new  CountryResource($this->country),
        'default_address'=> new  UserAddressResource($this->address),
      //  'address_list'=> new UserAddressResource($this->address_list->user_id)
      
             'address_list'=> $this->address_list,
       // 'country'=>$this->country_id,

        
    ];
    }
}