<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;

use App\Models\Message;

class MessageController extends Controller
{
    public function clientMessage(Request $request){

        
        $user_id = auth('sanctum')->user()->id;
     //  $user_id=222;

        $messageData = Message::where('user_id','=', $user_id)->orderBy('id', 'DESC')->limit(50)->get();

        $status= true;
        $message = 'Message fetched successfully';

        
        $response = [
            'status' => $status,
            'data'    => MessageResource::collection($messageData),
            'message' => $message,
        ];
        return response()->json($response, 200);
        
    }

    public function venderMessage(Request $request){
        
        
        $user_id = auth('sanctum')->user()->id;
     //  $user_id=222;

        $messageData = Message::where('user_id','=', $user_id)->orderBy('id', 'DESC')->limit(50)->get();

        $status= true;
        $message = 'Message fetched successfully';

        
        $response = [
            'status' => $status,
            'data'    => MessageResource::collection($messageData),
            'message' => $message,
        ];
        return response()->json($response, 200);
        
    }
}