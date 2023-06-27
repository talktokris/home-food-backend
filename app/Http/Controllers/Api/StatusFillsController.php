<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\StatusMsgResource;
use App\Models\Users_role;
use App\Models\Active_status_list;
use App\Models\Veg_status_list;
use App\Models\Comment_status_list;
use App\Models\Delivery_status_list;
use App\Models\Delivery_type_list;
use App\Models\Fetch_status_list;
use App\Models\Order_status_list;
use App\Models\Payment_status_list;
use App\Models\Payment_type_list;

class StatusFillsController extends Controller
{
    //

    public function status(){

        $active_status = Active_status_list::where('status','=',1)->get();
        $veg_status = Veg_status_list::where('status','=',1)->get();
        $comment_status = Comment_status_list::where('status','=',1)->get();
        $deliver_status = Delivery_status_list::where('status','=',1)->get();
        $deliver_type = Delivery_type_list::where('status','=',1)->get();
        $fetch_status = Fetch_status_list::where('status','=',1)->get();
        $order_status = Order_status_list::where('status','=',1)->get();
        $payment_status = Payment_status_list::where('status','=',1)->get();
        $payment_type = Payment_type_list::where('status','=',1)->get();



        return [
            'status' => 'success',
            'active_status' => StatusMsgResource::collection($active_status),
            'veg_status' => StatusMsgResource::collection($veg_status),
            'comment_status' => StatusMsgResource::collection($comment_status),
            'deliver_status' => StatusMsgResource::collection($deliver_status),
            'deliver_type' => StatusMsgResource::collection($deliver_type),
            'fetch_status' => StatusMsgResource::collection($fetch_status),
            'order_status' => StatusMsgResource::collection($order_status),
            'payment_status' => StatusMsgResource::collection($payment_status),
            'payment_type' => StatusMsgResource::collection($payment_type),
        ];

    }


    public function userRole(Request $request){

        $tokenUser = auth('sanctum')->user();

        $users_role = Users_role::where('status','=',1)->get();


        return response()->json([
            'status' => 'success',
            'users_role' => StatusMsgResource::collection($users_role),
          
        ]);

    }
}