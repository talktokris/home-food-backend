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


    public function orderMsgSave( array $messageArray){
        
        $saveMessage = false;
        
        foreach($messageArray as ['to_id' => $msg_to_user, 'title' => $msg_title, 'message' => $msg_text]){

            // list($msg_to_user, $b, $c) = $item;
            
            $saveMessage = new Message;
            $saveMessage->user_id = $msg_to_user;
            $saveMessage->title = $msg_title;
            $saveMessage->message = $msg_text;
            $saveMessage->read_status = 0;
            $saveMessage->status=1;
            $saveMessage->save();
    
            if(!$saveMessage){   $msgSave=false;  } else{   $msgSave=true;  }
        }
        return $saveMessage;

        
    }

             


}