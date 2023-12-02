<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodMenuImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        

        // return [
        //    // 'id'=>$this->id,
        //    // 'food_menu_id'=>$this->food_menu_id,
        //     'image_name'=>$this->image_name,
        // ];
    
    }
}