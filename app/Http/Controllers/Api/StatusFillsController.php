<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\StatusMsgResource;

use App\Models\Select_list_option;

class StatusFillsController extends Controller
{
    //

    public function status(){

        $active_status = Select_list_option::where('options_name','=','active_status_lists')->get();
        $veg_status = Select_list_option::where('options_name','=','veg_status_lists')->get();
        $comment_status = Select_list_option::where('options_name','=','comment_status_lists')->get();
        $deliver_status = Select_list_option::where('options_name','=','delivery_status_lists')->get();
        $deliver_type = Select_list_option::where('options_name','=','delivery_type_lists')->get();
        $fetch_status = Select_list_option::where('options_name','=','fetch_status_lists')->get();
        $halal_status = Select_list_option::where('options_name','=','halal_status_lists')->get();
        $order_status = Select_list_option::where('options_name','=','order_status_lists')->get();
        $payment_status = Select_list_option::where('options_name','=','payment_status_lists')->get();
        $payment_type = Select_list_option::where('options_name','=','payment_type_lists')->get();
        $food_category = Select_list_option::where('options_name','=','food_category_lists')->get();
        $pick_type = Select_list_option::where('options_name','=','extra_pick_type')->get();



        return [
            'status' => 'success',
            'active_status' => StatusMsgResource::collection($active_status),
            'veg_status' => StatusMsgResource::collection($veg_status),
            'comment_status' => StatusMsgResource::collection($comment_status),
            'deliver_status' => StatusMsgResource::collection($deliver_status),
            'deliver_type' => StatusMsgResource::collection($deliver_type),
            'fetch_status' => StatusMsgResource::collection($fetch_status),
            'halal_status' => StatusMsgResource::collection($halal_status),
            'order_status' => StatusMsgResource::collection($order_status),
            'payment_status' => StatusMsgResource::collection($payment_status),
            'payment_type' => StatusMsgResource::collection($payment_type),
            'food_category'=>StatusMsgResource::collection($food_category),
            'pick_type'=>StatusMsgResource::collection($pick_type),
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