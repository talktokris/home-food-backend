<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
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
        'id'=>$this->id,
        'user_id'=>$this->user_id,
        'address'=>$this->address,
        'city_name'=>$this->city_name,
        'state'=>$this->state,
        'postal_code'=>$this->postal_code,
       ];
    }
}