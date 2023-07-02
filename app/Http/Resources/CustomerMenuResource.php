<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerMenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       //return parent::toArray($request);

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'food_title' => $this->food_title,
            'food_description' => $this->food_description,
            'veg_status' => $this->veg_status,
           // 'default_image'=>$this->default_image,
           'default_image'=> new MenuImageResource($this->default_image),
   
            
        ];
    }
}