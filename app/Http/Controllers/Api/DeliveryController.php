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
use App\Http\Resources\SalesDeliveryResource;

class DeliveryController extends Controller
{

    public function deliveryOrdersPending(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $data= $request->all();
    

      //  $orderData= Sale::where('deliver_status','=',0)->with('orders.menu.default_image')->with('orders.delivery')->with('orders.pick_up')->get();
        $orderData= Sale::where('deliver_status','<=',4)->with('orders.menu.default_image','orders.delivery','orders.pick_up', 'orders.vender', 'orders.user' )->get();

        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Order searched successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => SalesDeliveryResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function deliveryOnTheWay(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $data= $request->all();
            
        //  $orderData= Sale::where('deliver_status','=',0)->with('orders.menu.default_image')->with('orders.delivery')->with('orders.pick_up')->get();
        $orderData= Sale::where('deliver_status','=',5)->with('orders.menu.default_image','orders.delivery','orders.pick_up', 'orders.vender', 'orders.user' )->get();

        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Order searched successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => SalesDeliveryResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function deliveryOrdersComplitedHistory(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $data= $request->all();
            
        //  $orderData= Sale::where('deliver_status','=',0)->with('orders.menu.default_image')->with('orders.delivery')->with('orders.pick_up')->get();
        $orderData= Sale::where('deliver_status','>=',6)->with('orders.menu.default_image','orders.delivery','orders.pick_up', 'orders.vender', 'orders.user' )->get();

        if(!$orderData){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $user_id; $message='Order searched successfully'; }
        if(count($orderData)>=1){ $success=true; $message='Here is your orders';}else{ $success=false; $message='No ordres found';}
        $response = [
            'success' => $success,
            'data'    => SalesDeliveryResource::collection($orderData),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }


    public function deliveryChangeStatus(Request $request){
        
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'sales_id' => 'required|integer|min:1|max:999999999999',
           // 'order_id' => 'required|integer|min:1|max:999999999999',
            'order_status' => 'required|integer|min:0|max:100',
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

            $data= $request->all();

            $dataOrders = Order::where('sales_id', $data['sales_id'])->get();

            foreach($dataOrders as $item){
                $order_id =$item->id;
                $updateItem = Order::where('id', '=',$order_id)->update(['delivery_user_id'=> $user_id, 'order_status'=> $data['order_status'], 'payment_status'=> 2]);
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $order_id; $message='Order status changed successfully'; }

            }

            if($data['order_status']==6){
                $payment_status_save=2;
            }else {$payment_status_save=1;}


            $updateSales = Sale::where('id', '=',$data['sales_id'])->update(['deliver_status'=> $data['order_status'],  'payment_status'=> $payment_status_save]);

            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['sales_id']; $message='Order completed successfully'; }


         /*

            $TotalOrderCount= Order::where('sales_id','=',$data['sales_id'])->get()->count();
     
            $TotalDeliverCount= Order::where('order_status','=',6)->where('sales_id','=',$data['sales_id'])->get()->count();
            if($TotalDeliverCount >= $TotalOrderCount){

                $updateSales = Sale::where('id', '=',$data['sales_id'])->update(['deliver_status'=> 6,  'payment_status'=> 2]);

                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $data['sales_id']; $message='Order completed successfully'; }
    
          
            }
            */
     
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