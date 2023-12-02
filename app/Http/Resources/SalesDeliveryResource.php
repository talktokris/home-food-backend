<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesDeliveryResource extends JsonResource
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
        'customer_amount'=>$this->customer_amount,
        'payment_type'=>$this->payment_type,
        'payment_status'=>$this->payment_status,
        'deliver_status'=>$this->deliver_status,
        'orders'=> $this->getOrderData($this->orders),
       // 'orderSet'=>$this->orders,
        //'orders'=> $this->getMenuData($this->orders),
        //'from'=>$this->from_address,
       // 'to'=>$this->to_address,
       // 'pickup'=> new  UserAddressResource($this->from_address),
        //'delivery'=> new  UserAddressResource($this->to_address),


       ];



    }

    public function getOrderData($data){

        $orders =[];
            foreach($data as $item){
                array_push($orders,  [
                    'id'=>$item->id,
                    "qty"=>$item->qty,
                    "total_customer_amount"=>$item->total_customer_amount,
                    "order_status"=>$item->order_status,
                    "pickup_address_id"=>$item->pickup_address_id,
                    "delivery_address_id"=>$item->delivery_address_id,
                    "payment_type"=>$item->payment_type,
                    "payment_status"=>$item->payment_status,
                    "delivery_type"=>$item->delivery_type,
                    "delivery"=>$this->getAddressData($item->delivery),
                    "pick_up"=>$this->getAddressData($item->pick_up),
                    "menu"=>$this->getMenuData($item->menu),
                    "vender"=>$this->getUserData($item->vender),
                    "user"=>$this->getUserData($item->user),
                    ]
                );
            }

        return $orders;

    }


    public function getUserData($data){

        return [
            "first_name"=>$data->first_name,
            "last_name"=>$data->last_name,
            "contact"=>$data->mobile_no,
            
        ];

    }

    public function getMenuData($data){

                return [
                    'id'=>$data->id,
                    "food_title"=>$data->food_title,
                    "food_description"=>$data->food_description,
                    "veg_status"=>$data->veg_status,
                    "image_name"=>$data->default_image->image_name,
                    
                ];
      
    }



    public function getAddressData($data){

            return [
                    'id'=>$data->id,
                    "address"=>$data->address,
                    "street"=>$data->street,
                    "city_name"=>$data->city_name,
                    "state"=>$data->state,
                    "postal_code"=>$data->postal_code,
            
            ];


    }
}