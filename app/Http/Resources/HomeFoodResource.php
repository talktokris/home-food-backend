<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeFoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
     //   return parent::toArray($request);


     return [
        'id'=>$this->id,
        'user_id'=>$this->user_id,
        'vender'=>$this->venderInfos($this->venderInfo),
        'food_title'=>$this->food_title,
        'food_description'=>$this->food_description,
        'veg_status'=>$this->veg_status,
        'halal_status'=>$this->halal_status,
        'rating'=>$this->rating,
        'food_category'=>$this->food_category,
        'image_name'=>$this->image_name,
        'vender_price'=>$this->vender_price,
        'customer_price'=>$this->customer_price,
        'discount_per'=>$this->discount_per,
        'active_status'=>$this->active_status,
       // 'topCommnets'=>$this->comments($this->topCommnets),

    ];
    }


            
                

    public function comments($data){
        $dataArray =[];
            foreach($data as $item){
                array_push($dataArray,  [
                    'id'=>$item->id,
                    'user_id'=>$item->user_id,
                    'rating'=>$item->rating,
                    "comments"=>$item->comments,
                    ]
                );
            }

        return $dataArray;
    }


    public function imagesSingle($data){
        return [
            'id'=>$data->id,
            "image_name"=>$data->image_name,
        ];
    }



    public function venderInfos($data){
        return [
            'id'=>$data->id,
            "name"=>$data->name,
            "first_name"=>$data->first_name,
            "last_name"=>$data->last_name,
            "banner_image"=>$data->banner_image,
            "location_lebel"=>$data->location_lebel,
            "altitude"=>$data->altitude,
            "latitude"=>$data->latitude,
            "rating"=>$data->rating,
        ];
    }

}