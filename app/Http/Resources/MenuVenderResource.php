<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuVenderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      //  return parent::toArray($request);

 
      return [
        'id' => $this->id,
        'name' => $this->name,
        'location_lebel'=>$this->location_lebel,
        'banner_image'=>$this->banner_image,
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'email' => $this->email,
        'altitude' => $this->altitude,
        'latitude' => $this->latitude,
        'rating' => $this->rating,
        'reviews'=>  $this->comments($this->reviews),

  
      ];
    }


    public function comments($data){
        $dataArray =[];
            foreach($data as $item){
                array_push($dataArray,  [
                    'id'=>$item->id,
                    'user_id'=>$item->user_id,
                    'comments'=>$item->comments,
                    "author"=>$item->author_name,
                    'rating'=>$item->rating,
                    ]
                );
            }

        return $dataArray;
    }
}