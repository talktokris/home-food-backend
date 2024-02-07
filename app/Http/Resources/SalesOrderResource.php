<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderResource extends JsonResource
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
        
        'total_items'=>$this->total_items,
        'deliver_fee'=>$this->deliver_fee,
        'tax'=>$this->tax,
        'customer_amount'=>$this->customer_amount,
        'grand_total'=>$this->grand_total,
        'payment_type'=>$this->payment_type,
        'deliver_status'=>$this->deliver_status,
        'vender_id'=>$this->vender_id,
        'order_status'=>$this->order_status,
        'order_string_value'=>$this->allStatus,
        'vender'=>$this->venderInfos($this->vender),
      //  'orders'=>$this->venderInfos($this->vender),
        'orders'=>$this->orderData($this->orders),
        
        // 'payment_status'=>$this->vender_price,
        // 'customer_price'=>$this->customer_price,
        // 'discount_per'=>$this->discount_per,
        // 'active_status'=>$this->active_status,
       // 'topCommnets'=>$this->comments($this->topCommnets),

    ];
    }


            
                
    public function orderData($data){
        $dataArray =[];
            foreach($data as $item){
                array_push($dataArray,  [
                    "id"=>$item->id,
                    "menu_id"=>$item->menu_id,
                    "customer_price"=>$item->customer_price,
                    "discount_per"=>$item->discount_per,
                    "price_after_discount"=>$item->price_after_discount,
                    "order_status"=>$item->order_status,
                    // "status_all"=>$this->orderStatusWord($item->allStatus),
                    "order_status_string"=>$item->allStatus,
                   // "order_status_word"=>$this->orderStatusWord($item->wordStatus),
                     "menu"=>$this->menuData($item->menu),
                    ]
                );
            }

        return $dataArray;
    }


    public function extra($data){
      //  return $data;
        $dataArray =[];
        foreach($data as $item){
            array_push($dataArray,  [
                "id"=>$item->id,
                "customer_price"=>$item->customer_price,
                "discount"=>$item->discount,
                "title"=>$item->extraInfo->description,
                "price"=>$item->extraInfo->price,
                // "heading"=>$item->extraInfo->heading->title,
                "heading"=>$item->extraInfo->heading,
             //   "extraInfo"=>$this->extraInfo($item->extraInfo),
                ]
            );
        }

    return $dataArray;
    }


    public function extraInfo($data){

      //  return $data;
        return [
            'id'=>$data->id,
            "description"=>$data->description,
            "food_description"=>$data->food_description,
            "price"=>$data->price,
            "heading_id"=>$data->heading_id,
            "heading_info"=>$data->heading->title,
            
         
        ];
    }


    public function menuData($data){
        if($data==null) {return "";}
        return [
            'id'=>$data->id,
            "food_title"=>$data->food_title,
            "food_description"=>$data->food_description,
            "image_name"=>$data->image_name,
            "veg_status"=>$data->veg_status,
            "halal_status"=>$data->halal_status,
            "customer_price"=>$data->customer_price,
            "discount_per"=>$data->discount_per,
           //  "extra"=>$data->extra,
           "extra"=>$this->extra($data->extra),
        ];
    }


    public function extraData($data){
        return [
            'id'=>$data->id,
            "food_title"=>$data->food_title,
            "food_description"=>$data->food_description,
            "image_name"=>$data->image_name,
            "veg_status"=>$data->veg_status,
            "halal_status"=>$data->halal_status,
            "customer_price"=>$data->customer_price,
            "discount_per"=>$data->discount_per,
            "extra"=>$data->extra,
        ];
    }

    public function orderStatusWord($data){
        return $data;
       // return $data->id;
        // $object = (object) $data;
        // return  $object->id;
        // return [
        //     'id'=>$data->id,
        //     'sting_value'=>$data->sting_value,

        // ];
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

    public function nullScape($value){
        if($value) {return $value;} else {return "";}
    }
}