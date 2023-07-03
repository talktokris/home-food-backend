<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VenderOrderStatusResource extends JsonResource
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
        'payment_type'=>$this->payment_type,
        'delivery_type'=>$this->delivery_type,
        'menu_id'=>$this->menu_id,
       // 'menu'=>  CustomerMenuResource::collection($this->menu),
        'menu'=> new  CustomerMenuResource($this->menu),
        'user'=> new  UserInfoForVenderResource($this->user),
        'delivery'=> new  UserAddressResource($this->delivery),

       // 'menu'=> $this->menu,
       // 'country'=> new  CountryResource($this->country),


        
    ];
    }
}