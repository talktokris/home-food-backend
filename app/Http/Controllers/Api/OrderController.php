<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sale;
use App\Models\Order;
use App\Models\Food_delivery_list;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Http\Resources\VenderOrderStatusResource;



class OrderController extends Controller
{
    public function clientStore(Request $request){

            $user_id = auth('sanctum')->user()->id;

        // $user_id=25;

        
            $validator = Validator::make($request->all(), [
                'payment_options' => 'required|integer|min:1|max:9999',
                'delivery_address' => 'required|integer|min:1|max:9999',
                
                ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();
            
        // $jsonData = '[{"data": {"active_status": 1, "customer_price": "110.00", "default_image": [], "discount_per": 0, "food_description": "Rice, Curd, Chicken", "food_title": "Nepali Thali", "id": 51, "images": [], "menu_profile_img_id": 26, "user_id": 23, "veg_status": 2, "vender_price": "100.00"}, "qnt": 3}, {"data": {"active_status": 1, "customer_price": "17.00", "default_image": [], "discount_per": 6, "food_description": "Fine Chocolate Silk Ice Cream [SWISS] 140 ML", "food_title": "GLOBO Ice Creams Of The World", "id": 48, "images": [], "menu_profile_img_id": 18, "user_id": 23, "veg_status": 1, "vender_price": "16.00"}, "qnt": 2}]';

        $jsonData=$data['orders'];
        $array = json_decode($jsonData);
        if(count($array)<1){
            return $this->sendError('Validation Error.', ['Cart Item Empty']);       

        }
        

                //  $calOutput= [];
                $payment_type=$data['payment_options'];
                $payment_status =0;
                $payment_id=0;
                $address_id=$data['delivery_address'];
                $vender_amount =  $this->totalCalculate($array, 'vender_price');
                $customer_amount =  $this->totalCalculate($array, 'customer_price');
                $margin_amount =  $customer_amount-$vender_amount;
                $total_items = count($array);

        
            $saveItem = new Sale;
            $saveItem->user_id= $user_id;
            $saveItem->total_items=$total_items;
            $saveItem->vender_amount= $vender_amount;
            $saveItem->margin_amount= $margin_amount;
            $saveItem->customer_amount= $customer_amount;
            $saveItem->payment_type= $payment_type;
            $saveItem->payment_status= $payment_status;
            $saveItem->payment_id= $payment_id;
            $saveItem->status= 1;
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{  
                $sales_id= $saveItem->id;
                $saveDelivary = $this->saveDelivery($sales_id, $address_id);
            //   echo "Saved Delivery :".$saveDelivary."</br>";
            
            }

            foreach($array as $item){

                // echo "<pre>";
                // echo print_r($item);
                // echo "</pre>";
                $venderInfo = User::where('id', $item->data->user_id)->get();

                if(count($venderInfo)>0){
                    $marginPercentage =$venderInfo[0]->app_margin_per;
                    $venderAddress =$venderInfo[0]->address_id;
                } else {
                    $marginPercentage=10;
                }
                $discountPercentage = $item->data->discount_per;

                $qty =$item->qnt;
            //  dd($discountPercentage);


            //<========== Vender Price Find if Discount ==============>

                $vender_price = $item->data->vender_price;
                if($discountPercentage>=1){
                $vPriceDiscount =$vender_price/100*$discountPercentage;
                }else {$vPriceDiscount=0;}
            $newVenderPrice = $vender_price-$vPriceDiscount;
            
            //echo "Vender: ".$vender_price."-".$discountPercentage.'-'.$newVenderPrice."</br>";
            //echo "</br>";

                //<========== Customer Price Adding Margin =============>
            if($marginPercentage>=1){
                $vPriceMargin =$newVenderPrice/100*$marginPercentage;
                }else {$vPriceMargin=0;}

                $newCustomerPrice= $newVenderPrice+ $vPriceMargin;

            //  echo "Customer: ". $newVenderPrice."-".$marginPercentage.'-'.$newCustomerPrice."</br>";
            
                $total_vender_amount=$newVenderPrice*$qty;
                $total_customer_amount=$newCustomerPrice*$qty;

                $saveItem = new Order;
                $saveItem->sales_id= $sales_id;
                $saveItem->user_id=$user_id;
                $saveItem->vender_id= $item->data->user_id;
                $saveItem->menu_id= $item->data->id;
                $saveItem->vender_price= $item->data->vender_price;
                $saveItem->discount_per= $discountPercentage;
                $saveItem->price_after_discount= $newVenderPrice;
                $saveItem->margin_per= $marginPercentage;
                $saveItem->customer_price= $newCustomerPrice;
                $saveItem->qty= $qty;
                $saveItem->total_vender_amount= $total_vender_amount;
                $saveItem->total_customer_amount= $total_customer_amount;
                $saveItem->order_status= 1;
                $saveItem->pickup_address_id=$venderAddress;
                $saveItem->delivery_address_id=$address_id;
                $saveItem->payment_type= $payment_type;
                $saveItem->payment_status= 1;
                $saveItem->payment_id= 0;
                $saveItem->delivery_type= 2;
                $saveItem->delivery_user_id= 0;
                $saveItem->save();

            }

            if(!$saveItem){   $success=false;   $get_id = $sales_id; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $sales_id; $message='Order Placed Successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);

    

        
    }

    public function saveDelivery($sales_id, $address_id){

        $saveItem = new Food_delivery_list;
        $saveItem->sales_id= $sales_id;
        $saveItem->fetch_status=1;
        $saveItem->address_id=$address_id;
        $saveItem->save();

        if(!$saveItem){    $get_id = 0;  }
        else{   $get_id = $saveItem->id;; }
        
        return $get_id;

    }


    public function totalCalculate($array, $priceName){

        $totalPrice=0;
        
        foreach ($array as $key=>$item){
            $price = $item->data->$priceName;
            $qty = $item->qnt;
            $totalPrice+=$price*$qty;

        }
        return $totalPrice;
    }

   
  
    public function clientPending(Request $request){

        $user_id = auth('sanctum')->user()->id;
       // $user_id=25;

        //return $user_id;
        $orderData= Order::where('user_id','=',$user_id)->where('order_status','!=',6)->with('menu.default_image')->with('vender')->get();

        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Item updated successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your pending orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => CustomerPendingOrderResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function venderOrdersStatus(Request $request){

        $user_id = auth('sanctum')->user()->id;
      //  $user_id=23;

        //return $user_id;


        $validator = Validator::make($request->all(), [
            'order_status' => 'required|integer|min:0|max:10',
            
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();
        $order_status= $data['order_status'];

        //return $user_id;
        $orderData= Order::where('vender_id','=',$user_id)->where('order_status','=',$order_status)->with('menu.default_image')->with('delivery')->with('user')->get();


        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Order searched successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => VenderOrderStatusResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function venderOrdersRunning(Request $request){

        $user_id = auth('sanctum')->user()->id;
      //  $user_id=23;

        //return $user_id;



        $data= $request->all();
    

        //return $user_id;
       // $orderData= Order::where('vender_id','=',$user_id)->where('order_status','=',$order_status)->with('menu.default_image')->with('delivery')->with('user')->get();
        $orderData= Order::where('vender_id','=',$user_id)->where(function ($query) {
            $query->where('order_status', '=', 2)
                  ->orWhere('order_status', '=', 3);
        })->with('menu.default_image')->with('delivery')->with('user')->get();

        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Order searched successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => VenderOrderStatusResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function venderOrdersReadyForDelivery(Request $request){

        $user_id = auth('sanctum')->user()->id;
      //  $user_id=23;

        //return $user_id;



        $data= $request->all();
    

        //return $user_id;
        $orderData= Order::where('vender_id','=',$user_id)->where('order_status','=',4)->with('menu.default_image')->with('delivery')->with('user')->get();
       /* $orderData= Order::where('vender_id','=',$user_id)->where(function ($query) {
            $query->where('order_status', '=', 2)
                  ->orWhere('order_status', '=', 3);
        })->with('menu.default_image')->with('delivery')->with('user')->get();
*/
        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Order searched successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => VenderOrderStatusResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }



    public function venderOrdersComplitedHistory(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $data= $request->all();
    

        $orderData= Order::where('vender_id','=',$user_id)->where('order_status','>',4)->with('menu.default_image')->with('delivery')->with('user')->get();

        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Order searched successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => VenderOrderStatusResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }




    public function clientOrderHistory(Request $request){
        
    }

    public function venderChangeStatus(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:999999999999',
            'order_status' => 'required|integer|min:0|max:100',
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

            $data= $request->all();

            $updateItem = Order::where('id', '=',$data['id'])->where('vender_id', '=',$user_id)->update(['order_status'=> $data['order_status']]);
        
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Order status changed successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);
    }


    public function sendError($error, $errorMessages = [], $code = 404) {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    
}