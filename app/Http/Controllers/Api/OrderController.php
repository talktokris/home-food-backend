<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sale;
use App\Models\Order;
use App\Models\Food_menu;
use App\Models\Food_menu_argument_item;
use App\Models\Orders_menu_arrgument;
use App\Models\Food_delivery_list;
use App\Models\Select_list_option;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Http\Resources\VenderOrderStatusResource;
use App\Http\Resources\CustomerPendingOrderResource;
use App\Http\Resources\SalesOrderResource;
use App\Http\Resources\SalesOrderClientResource;
use App\Http\Controllers\Api\MessageController;








class OrderController extends Controller
{
    public function clientStore(Request $request){

            $user_id = auth('sanctum')->user()->id;
            $tax= 6 ;// GST Tax Percentage 

            $pre_order_status = 1 ; // 1 for by passing delivery accpet app, 0  for delivery app to accept the order. 

     //   $user_id=25;

        
            $validator = Validator::make($request->all(), [
                'payment_options' => 'required|integer|min:1|max:9999',
                'delivery_address' => 'required|integer|min:1|max:9999',
                
                ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

      $data= $request->all();
       // return  $data;
            /*
                $testData = {
                    "user_id": 4,
                    "orders": {
                    "userId": 4,
                    "venderId": 32,
                    "totalItems": 2,
                    "totalPrice": 24,
                    "deliverFee": 5,
                    "deliveryAddress": {
                        "id": 5,
                        "user_id": 25,
                        "address": "sadfsadf4 7",
                        "street": "asdfsadfa 7",
                        "city_name": "Kuala Lumpur 7",
                        "state": "Malacca",
                        "postal_code": 234347,
                        "default_status": 1,
                        "status": 1,
                        "created_at": "2023-06-27T13:42:43.000000Z",
                        "updated_at": "2023-06-28T05:57:51.000000Z"
                    },
                    "paymentType": 1,
                    "paymentStatus": 0,
                    "paymentId": 0,
                    "orders": [
                        {
                        "foodId": 59,
                        "extra": []
                        },
                        {
                        "foodId": 58,
                        "extra": []
                        }
                    ]
                    },
                    "payment_options": 1,
                    "delivery_address": 5
                };
                        
                
            */     
        $client_id = $data['user_id'];
        $payment_options = $data['payment_options'];
        $delivery_address = $data['delivery_address'];
        $ordersData=$data['orders'];



        if(count($ordersData)<1){
            return $this->sendError('Validation Error.', ['Cart Item Empty']);       

        }
 
            $userId=$ordersData['userId'];
            $venderId=$ordersData['venderId'];
            $totalItems =$ordersData['totalItems'];
            $totalPrice =$ordersData['totalPrice'];
            $deliverFee =$ordersData['deliverFee'];
            $deliveryAddress =$ordersData['deliveryAddress'];
            $paymentType =$ordersData['paymentType'];
            $paymentStatus =$ordersData['paymentStatus'];
            $paymentId =$ordersData['paymentId'];
            $food_orders =$ordersData['orders'];

            $total_vender_amount =0;
            $total_margin_amount=0;
            $total_customer_amount=0;
            $total_tax_amount =0;
          //  return "Hi";
            $total_vender_amount =  $this->totalCalculate($food_orders, 'vender_price');
            
          //  return $total_vender_amount;

            $total_customer_amount =  $this->totalCalculate($food_orders, 'customer_price');
           // return $total_customer_amount;
            $total_margin_amount =  round($total_customer_amount-$total_vender_amount, 2);
           // return $total_margin_amount;
            $taxAmount =($total_customer_amount/100)*$tax;
            $total_tax_amount = round($taxAmount, 1);
   
           $grand_total = $total_customer_amount+$total_tax_amount+$deliverFee;

            $total_items = count($food_orders);
        
            $saveItem = new Sale;
            $saveItem->user_id= $user_id;
            $saveItem->vender_id = $venderId;
            $saveItem->total_items=$totalItems;
            $saveItem->vender_amount= $total_vender_amount;
            $saveItem->margin_amount= $total_margin_amount;
            $saveItem->deliver_fee= $deliverFee;
            $saveItem->tax= $total_tax_amount;
            $saveItem->customer_amount= $total_customer_amount;
            $saveItem->grand_total= $grand_total;
            $saveItem->payment_type= $paymentType;
            $saveItem->payment_status= $paymentStatus;
            $saveItem->payment_id= $paymentId;
            $saveItem->deliver_address_id= $delivery_address;
            $saveItem->deliver_status =0;
            $saveItem->order_status =$pre_order_status;
            $saveItem->status= 1;
            $saveItem->save();

           // return $saveItem;


            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{  
                $sales_id= $saveItem->id;
              // return $delivery_address;
                $saveDelivary = $this->saveDelivery($sales_id, $delivery_address);
             //  return "Saved Delivery :".$sales_id.'--'.$saveDelivary."</br>";


                // Sending Message to user and vender start
                $statusMsg = Select_list_option::where('options_name', '=','order_status_lists')->where('integer_value', '=', $pre_order_status)->get()->first();
                // return $salesInfo->user_id;
                $statusSting =  $statusMsg->sting_value;
    
                $client_id = $user_id;
                $clinet_title = 'Your order no : ' .$sales_id .' has placed successfully.';
                $client_message = 'Dear user, Your order is placed successfully. Your order no is :  '.$sales_id .'. You will be informed about the process update.';
    
                $vender_id = $venderId;
                $vender_title = 'Your have new pending order no : '.$sales_id .'. Please accept to proceed it.';
                $vender_message = 'Dear user, Your have new pending order no :  '.$sales_id .'. Please accept to proceed it.' ;
    
                $messageArray = [
                                ['to_id'=>$client_id, 'title'=>$clinet_title, 'message'=>$client_message ],
                                ['to_id'=>$vender_id, 'title'=>$vender_title, 'message'=>$vender_message]
                            ];
    
                $msg = new  MessageController;
                $msg->orderMsgSave($messageArray);
                // Sending Message to user and vender end

            
            }

            foreach($food_orders as $item){

                
                $new_food_id = $item['foodId'];
                $arg_arry_data = $item['extra'];
                $newFoodData = Food_menu::where('id', $new_food_id)->get();
                $qty=1;
                //return $newFoodData;

                $vender_id = $newFoodData[0]->user_id;
                $vender_price= $newFoodData[0]->vender_price;
                $discount_per= $newFoodData[0]->discount_per;

                $discount_per =6;
          
                $venderInfo = User::where('id', $vender_id)->get();

                if(count($venderInfo)>0){
                    $marginPercentage =$venderInfo[0]->app_margin_per;
                    $venderAddress =$venderInfo[0]->address_id;
                } else {
                    $marginPercentage=10;
                    $venderAddress=0;
                }
                $discountPercentage = $discount_per;

            //  dd($discountPercentage);


            //<========== Vender Price Find if Discount ==============>

                if($discountPercentage>=1){
                $vPriceDiscount =$vender_price/100*$discountPercentage;
                } else {$vPriceDiscount=0;}
            $newVenderPrice = $vender_price-$vPriceDiscount;
 

                //<========== Customer Price Adding Margin =============>
            if($marginPercentage>=1){
                $vPriceMargin =$newVenderPrice/100*$marginPercentage;
                }else {$vPriceMargin=0;}

                $newCustomerPrice= $newVenderPrice+ $vPriceMargin;

            
                $total_vender_amount=$newVenderPrice*$qty;
                $total_customer_amount=$newCustomerPrice*$qty;

                $saveItemMenu = new Order;
                $saveItemMenu->sales_id= $sales_id;
                $saveItemMenu->user_id=$user_id;
                $saveItemMenu->vender_id= $vender_id;
                $saveItemMenu->menu_id= $new_food_id;
                $saveItemMenu->vender_price= $vender_price;
                $saveItemMenu->discount_per= $discountPercentage;
                $saveItemMenu->price_after_discount= $newVenderPrice;
                $saveItemMenu->margin_per= $marginPercentage;
                $saveItemMenu->customer_price= $newCustomerPrice;
                $saveItemMenu->qty= $qty;
                $saveItemMenu->total_vender_amount= $total_vender_amount;
                $saveItemMenu->total_customer_amount= $total_customer_amount;
                $saveItemMenu->order_status= 1;
                $saveItemMenu->pickup_address_id=$venderAddress;
                $saveItemMenu->delivery_address_id=$delivery_address;
                $saveItemMenu->payment_type= $paymentType;
                $saveItemMenu->payment_status= $paymentStatus;
                $saveItemMenu->payment_id= $paymentId;
                $saveItemMenu->delivery_type= 2;
                $saveItemMenu->delivery_user_id= 0;

                $saveItemMenu->save();

                if(!$saveItemMenu){   $success=false;   $order_id = 0; $message='Unknown Error, Plz Contact support'; }
                else{     $order_id= $saveItemMenu->id;   }
                
              //  return $saveItemMenu;

                foreach($arg_arry_data as $key=>$argItem){

                    $arg_id=$argItem;
                    $fetchArgData = Food_menu_argument_item::where('id', $arg_id)->get();
                    $argument_item_id=$fetchArgData[0]->id;
                    $argument_vender_price=$fetchArgData[0]->vender_price;
                    $argument_price=$fetchArgData[0]->price;

                    $dis_vender_price =  ($argument_vender_price/100)*$discountPercentage;
                    $cal_vender_price =  $argument_vender_price-$dis_vender_price;
                    $dis_customer_price =  ($argument_price/100)*$discountPercentage;
                    $cal_customer_price =  $argument_price-$dis_customer_price;
                
                
                    $saveArgMenu = new Orders_menu_arrgument;
                    $saveArgMenu->sales_id= $sales_id;
                    $saveArgMenu->menu_id= $new_food_id;
                    $saveArgMenu->order_id=$order_id;
                    $saveArgMenu->user_id=$user_id;
                    $saveArgMenu->vender_id=$vender_id;
                    $saveArgMenu->argument_item_id=$argument_item_id;
                    $saveArgMenu->vender_price= $cal_vender_price;
                    $saveArgMenu->customer_price= $cal_customer_price;
                    $saveArgMenu->discount= $discountPercentage;
                    $saveArgMenu->status= 1;
                   // return $saveArgMenu;
                    $saveArgMenu->save();

                }




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



   
  
    public function clientPending(Request $request){

        $user_id = auth('sanctum')->user()->id;
          $user_id=25;

        // return $user_id;
      //  $orderData= Order::where('user_id','=',$user_id)->where('order_status','!=',6)->with('menu.extra')->with('vender')->get();

      
        // $orderData= Sale::where('user_id','=',$user_id)->where('deliver_status','=',0)->with('vender')->with('orders.menu.extra.extraInfo')->get();
        $orderData= Sale::where('user_id','=',$user_id)->where('deliver_status','=',0)->with('vender')->with('orders.menu.extra.extraInfo.heading')->orderBy('id', 'DESC')->get();
        // return $orderData;

     //   $orderData= Sale::where('user_id','=',$user_id)->where('deliver_status','==',0)->with('menu.extra')->with('vender')->get();

        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Item updated successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your pending orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => SalesOrderClientResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }
    

    public function clientOrderHistory(Request $request){
        
    }
    
    public function venderOrdersPending(Request $request){

        $user_id = auth('sanctum')->user()->id;

        // $user_id=23;
    

        $validator = Validator::make($request->all(), [
            'order_status' => 'required|integer|min:0|max:20',
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

          $data= $request->all();

        $order_status = $data['order_status'];
      //  $orderData= Order::where('user_id','=',$user_id)->where('order_status','!=',6)->with('menu.extra')->with('vender')->get();

  
        // $orderData= Sale::where('user_id','=',$user_id)->where('deliver_status','=',0)->with('vender')->with('orders.menu.extra.extraInfo')->get();
        $orderData= Sale::where('vender_id','=',$user_id)->where('deliver_status','=',0)->where('order_status','=',1)->with('vender')->with('orders.menu.extra.extraInfo.heading')->orderBy('id', 'DESC')->get();
    //    return $orderData;

     //   $orderData= Sale::where('user_id','=',$user_id)->where('deliver_status','==',0)->with('menu.extra')->with('vender')->get();

        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Item updated successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your pending orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => SalesOrderResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function venderOrdersRunning(Request $request){

        $user_id = auth('sanctum')->user()->id;

      //  $user_id=25;

        $validator = Validator::make($request->all(), [
            'order_status' => 'required|integer|min:0|max:20',
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

          $data= $request->all();

        $order_status = $data['order_status'];
      //  $orderData= Order::where('user_id','=',$user_id)->where('order_status','!=',6)->with('menu.extra')->with('vender')->get();

      
        // $orderData= Sale::where('user_id','=',$user_id)->where('deliver_status','=',0)->with('vender')->with('orders.menu.extra.extraInfo')->get();
        $orderData= Sale::where('vender_id','=',$user_id)->where('deliver_status','=',0)->whereBetween('order_status', [0, 3])->where('order_status','=',$order_status)->with('vender')->with('orders.menu.extra.extraInfo')->orderBy('id', 'DESC')->get();
       // return $orderData;

     //   $orderData= Sale::where('user_id','=',$user_id)->where('deliver_status','==',0)->with('menu.extra')->with('vender')->get();

        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Item updated successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your pending orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => SalesOrderResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function venderOrdersReadyForDelivery(Request $request){


        $user_id = auth('sanctum')->user()->id;

        // $user_id=25;

        $validator = Validator::make($request->all(), [
            'order_status' => 'required|integer|min:0|max:20',
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

          $data= $request->all();

        $order_status = $data['order_status'];


      //  $orderData= Order::where('user_id','=',$user_id)->where('order_status','!=',6)->with('menu.extra')->with('vender')->get();

      
        // $orderData= Sale::where('user_id','=',$user_id)->where('deliver_status','=',0)->with('vender')->with('orders.menu.extra.extraInfo')->get();
        $orderData= Sale::where('vender_id','=',$user_id)->where('deliver_status','=',0)->whereBetween('order_status', [5, 5])->with('vender')->with('orders.menu.extra.extraInfo')->orderBy('id', 'DESC')->get();
      //  $orderData= Sale::where('vender_id','=',$user_id)->where('deliver_status','=',0)->whereBetween('order_status', [2, 5])->with('vender')->with('orders.menu.extra.extraInfo')->orderBy('id', 'DESC')->get();
       
        // return $orderData;
    //    return $orderData;

     //   $orderData= Sale::where('user_id','=',$user_id)->where('deliver_status','==',0)->with('menu.extra')->with('vender')->get();

        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Item updated successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your pending orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => SalesOrderResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }




    public function venderChangeStatus(Request $request){
        
        $user_id = auth('sanctum')->user()->id;
        //  $user_id = 23;
   
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:999999999999',
            'sales_id' => 'required|integer|min:1|max:999999999999',
            'order_status' => 'required|integer|min:0|max:100',
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

            $data= $request->all();
            $sales_id=$data['sales_id'];




            $updateCheckCount = Sale::where('id', '=',$sales_id)->where('vender_id', '=',$user_id)->count();

            if($updateCheckCount<=0){
                $response = [
                    'success' => false,
                    'data'    => $user_id,
                    'message' => 'Unauthorized Attempt',
                ];
                return response()->json($response, 200);
            }

            // Send Message Start 
            
            $orderInfo = Order:: where('id', '=', $data['id'])->with('menu')->get()->first();
            $itemName = $orderInfo->menu->food_title;
            $salesInfo = Sale:: where('id', '=', $data['sales_id'])->get()->first();
            $statusMsg = Select_list_option::where('options_name', '=','order_status_lists')->where('integer_value', '=', $data['order_status'])->get()->first();
            // return $salesInfo->user_id;
            $statusSting =  $statusMsg->sting_value;

            $client_id = $salesInfo->user_id;
            $clinet_title = $itemName.' of order no : '.$data['sales_id'] .' is ' . $statusSting ;
            $client_message = 'Dear User, your order no : '.$data['sales_id'] .' status is changed to ' .$statusSting ;

            $vender_id = $salesInfo->vender_id;
            $vender_title = $itemName.' of order no : '.$data['sales_id'] .' is ' . $statusSting ;
            $vender_message = 'Dear Vender, '. $itemName.' of order no : '.$data['sales_id'] .' status is changed to ' .$statusSting ;

            $messageArray = [
                            ['to_id'=>$client_id, 'title'=>$clinet_title, 'message'=>$client_message ],
                            ['to_id'=>$vender_id, 'title'=>$vender_title, 'message'=>$vender_message]
                        ];
            // return $messageArray;

            $msg = new  MessageController;
            $msg->orderMsgSave($messageArray);

            // Send Message End 
       

            $updateItem = Order::where('id', '=',$data['id'])->where('vender_id', '=',$user_id)->update(['order_status'=> $data['order_status']]);
        
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Order status changed successfully'; }

   
            $TotalOrderCount= Order::where('sales_id','=',$sales_id)->get()->count();

        
     
            $TotalDeliverCount= Order::where('order_status','=',$data['order_status'])->where('sales_id','=',$sales_id)->get()->count();
            if($TotalDeliverCount == $TotalOrderCount){
               
               // $updateSales = Sale::where('id', '=',$sales_id)->update(['deliver_status'=> $data['order_status'],  'payment_status'=> 2]);
                $updateSales = Sale::where('id', '=',$sales_id)->update(['order_status'=> $data['order_status']]);
           
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $data['id']; $message='Order status changed successfully'; }
    
               // return $message;
            }
     
            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);
    }


    public function venderSalesStatus(Request $request){
        
        $user_id = auth('sanctum')->user()->id;
        $user_id=23;



        $validator = Validator::make($request->all(), [
            'sales_id' => 'required|integer|min:1|max:999999999999',
            'status_value' => 'required|integer|min:0|max:100',
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

            $data= $request->all();


            $updateCheckCount = Sale::where('id', '=',$data['sales_id'])->where('vender_id', '=',$user_id)->count();

            if($updateCheckCount<=0){
                $response = [
                    'success' => false,
                    'data'    => $user_id,
                    'message' => 'Unauthorized Attempt',
                ];
                return response()->json($response, 200);
            }
  

            $updateSalesStatus = Sale::where('id', '=',$data['sales_id'])->where('vender_id', '=',$user_id)->update(['order_status'=> $data['status_value']]);

            // Send Message Start 
            
            $salesInfo = Sale:: where('id', '=', $data['sales_id'])->get()->first();
            $statusMsg = Select_list_option::where('options_name', '=','order_status_lists')->where('integer_value', '=', $data['status_value'])->get()->first();
            // return $salesInfo->user_id;
            $statusSting =  $statusMsg->sting_value;

            $client_id = $salesInfo->user_id;
            $clinet_title = 'Order no : '.$data['sales_id'] .' status is changed to ' . $statusSting ;
            $client_message = 'Dear User, Your order no : '.$data['sales_id'] .' status is changed to ' .$statusSting ;

            $vender_id = $salesInfo->vender_id;
            $vender_title ='Order no : '.$data['sales_id'] .' status is changed to ' . $statusSting ;
            $vender_message = 'Dear Vender,  Your order no : '.$data['sales_id'] .' status is changed to ' .$statusSting ;

            $messageArray = [
                            ['to_id'=>$client_id, 'title'=>$clinet_title, 'message'=>$client_message ],
                            ['to_id'=>$vender_id, 'title'=>$vender_title, 'message'=>$vender_message]
                        ];

            $msg = new  MessageController;
            $msg->orderMsgSave($messageArray);

            // Send Message End 

        if($updateSalesStatus){

            $getOrders = Order::where('sales_id', '=',$data['sales_id'])->where('vender_id', '=',$user_id)->get();
            // return $getOrders;

            foreach($getOrders as $row){
                $order_id =  $row->id;
              $updateOrders = Order::where('id', '=',$order_id)->where('vender_id', '=',$user_id)->update(['order_status'=> $data['status_value']]);

            }

            if(!$updateOrders){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['sales_id']; $message='Order status changed successfully'; }
            
            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);
        }
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

        // $foodId = $array[0]['foodId'];

        $totalPrice=0;
        
        foreach ($array as $key=>$item){
            $food_id = $item['foodId'];
            $foodData = Food_menu::where('id', $food_id)->get();
            // return $foodData;

            $price = $foodData[0]->$priceName;
            $discount = $foodData[0]->discount_per;
           // $discount=6;
            $discountAmount = ($price/100)*$discount;

            $extraArray = $item['extra'];
           // $extraArray =[1,2,3];

            $totalArgPrice=0;
            foreach ($extraArray as $key =>$arg_item){
                $arg_data = Food_menu_argument_item::where('id', $arg_item)->get();
                    $getPrice=0;
                    if($priceName=='vender_price'){
                        $getPrice =$arg_data[0]->vender_price;

                    } else{
                        $getPrice =$arg_data[0]->price;

                    }
                    $argDiscountAmount = ($getPrice/100)*$discount;

                    $totalArgPrice+= $getPrice-$argDiscountAmount;


            }
          //  return $totalArgPrice;

            $calPrice = $price - $discountAmount;

            $priceWithArg = $calPrice + $totalArgPrice;


          $totalPrice+=$priceWithArg;

        }
        return round($totalPrice, 2);
    }

    
}