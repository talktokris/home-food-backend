<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodMenuResource extends JsonResource
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
           // 'vender'=>$this->venderInfos($this->venderInfo),
            'food_title'=>$this->food_title,
            'food_description'=>$this->food_description,
            'veg_status'=>$this->veg_status,
            'halal_status'=>$this->halal_status,
            'food_category'=>$this->food_category,
            'food_category_id'=>$this->food_category_id,
            'rating'=>$this->rating,
            'image_name'=>$this->image_name,
            'vender_price'=>$this->vender_price,
            'customer_price'=>$this->customer_price,
            'discount_per'=>$this->discount_per,
            'menu_profile_img_id'=>$this->menu_profile_img_id,
            'active_status'=>$this->active_status,
           // 'default_image'=>  $this->imagesSingle($this->default_image),
          //  'default_image'=> new  FoodMenuImageResource($this->default_image),
            // 'images'=> new  FoodMenuImageResource($this->images),
          // 'images'=> $this->imagesMultiple($this->images),
         //   'topCommnets'=>$this->comments($this->topCommnets),
            'arguments'=>$this->argumentInfo($this->arguments),

        ];




    }
                
                
                

    public function argumentItems($data){
        $dataArray =[];
            foreach($data as $item){

                if($item->delete_status==0){
                    
                    array_push($dataArray,  [
                        'id'=>$item->id,
                        "description"=>$item->description,
                        "vender_price"=>$item->vender_price,
                        "price"=>$item->price,
                        "status"=>$item->status,
                        ]
                    );
                }
            }

        return $dataArray;
    }

    public function argumentInfo($data){
        $dataArray =[];
            foreach($data as $item){
                array_push($dataArray,  [
                    'id'=>$item->id,
                    "food_menu_id"=>$item->food_menu_id,
                    "title"=>$item->title,
                    "pick_type"=>$item->pick_type,
                    "status"=>$item->status,
                    "data"=>$item->pick_type,
                    "list"=>$this->argumentItems($item->argumentList),
                    ]
                );
            }

        return $dataArray;
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

    public function imagesMultiple($data){
        $imagesList =[];
            foreach($data as $item){
                array_push($imagesList,  [
                    'id'=>$item->id,
                    "image_name"=>$item->image_name,
                    ]
                );
            }

        return $imagesList;
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