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
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'email'=>$this->email,
        'mobile_no' => $this->mobile_no,
        'device_name'=>$this->device_name,
        'app_margin_per'=>$this->app_margin_per,
        'role'=> new RoleResource($this->role),
        'country'=> new  CountryResource($this->country),
        'default_address'=> $this->address,
      //  'address_list'=> new UserAddressResource($this->address_list->user_id)
      
             'address_list'=> $this->address_list,
       // 'country'=>$this->country_id,

        
    ];
    }
}