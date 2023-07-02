<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerPendingOrderResource extends JsonResource
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
            'sales_id' => $this->sales_id,
            'user_id' => $this->user_id,
            'vender_id' => $this->vender_id,
            'vender_price' => $this->vender_price,
            'discount_per'=>$this->discount_per,
            'margin_per'=>$this->margin_per,
            'customer_price'=>$this->customer_price,
            'qty'=>$this->qty,
            'order_status'=>$this->order_status,
            'total_amount'=>$this->total_customer_amount,
            'menu_id'=>$this->menu_id,
           // 'menu'=>  CustomerMenuResource::collection($this->menu),
            'menu'=> new  CustomerMenuResource($this->menu),

           // 'menu'=> $this->menu,
           // 'country'=> new  CountryResource($this->country),
   
    
            
        ];
        /*

        'sales_id',
        'user_id',
        'vender_id',
        'menu_id',
        'vender_price',
        'discount_per',
        'price_after_discount',
        'margin_per',
        'customer_price',
        'qty',
        'total_vender_amount',
        'total_customer_amount',
        'order_status',
        'pickup_address_id',
        'payment_type',
        'payment_status',
        'payment_id',
        'delivery_type',
        'delivery_user_id',
        */
    }
}