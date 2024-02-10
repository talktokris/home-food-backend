<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

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
    public function clientMessageReadCount(Request $request){
        
        
        $user_id = auth('sanctum')->user()->id;
     //  $user_id=222;

        $messageDataCount = Message::where('user_id','=', $user_id)->where('read_status','=', 0)->get()->count();

        $status= true;
        $message = 'Message fetched successfully';

        
        $response = [
            'status' => $status,
            'data'    => $messageDataCount,
            'message' => $message,
        ];
        return response()->json($response, 200);
        
    }

    public function clientMessageReadUpdate(Request $request){
        
        
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0|max:9999999',
            
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $data= $request->all();
            $message_id= $data['id'];

            $updateItem = Message::where('id', '=',$message_id)->update(['read_status'=> 1]);

            if(!$updateItem){   $success=false;   $get_id = $message_id; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $message_id; $message='Message Seen successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);
        
    }

 



    public function venderMessage(Request $request){
        
        
        $user_id = auth('sanctum')->user()->id;
     //  $user_id=222;

        $messageData = Message::where('user_id','=', $user_id)->orderBy('id', 'DESC')->limit(100)->get();

        $status= true;
        $message = 'Message fetched successfully';

        
        $response = [
            'status' => $status,
            'data'    => MessageResource::collection($messageData),
            'message' => $message,
        ];
        return response()->json($response, 200);
        
    }

    public function venderMessageReadCount(Request $request){
        
        
        $user_id = auth('sanctum')->user()->id;
     //  $user_id=222;

        $messageDataCount = Message::where('user_id','=', $user_id)->where('read_status','=', 0)->get()->count();

        $status= true;
        $message = 'Message fetched successfully';

        
        $response = [
            'status' => $status,
            'data'    => $messageDataCount,
            'message' => $message,
        ];
        return response()->json($response, 200);
        
    }

    public function venderMessageReadUpdate(Request $request){
   
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0|max:9999999',
            
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $data= $request->all();
            $message_id= $data['id'];

            $updateItem = Message::where('id', '=',$message_id)->update(['read_status'=> 1]);

            if(!$updateItem){   $success=false;   $get_id = $message_id; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $message_id; $message='Message Seen successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
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