<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User_address_list;
use App\Models\User;

class AddressController extends Controller
{
    public function store(Request $request){

        $user_id = auth('sanctum')->user()->id;

       

        $validator = Validator::make($request->all(), [
            'address' => 'required|string|min:5|max:200',
            'street' => 'required|string|min:2|max:200',
            'city_name' => 'required|string|min:2|max:100',
            'state' => 'required|string|min:3|max:100',
            'postal_code' => 'required|integer|min:0|max:9999999',

            
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $data= $request->all();
        $saveItem = new User_address_list;
        $saveItem->user_id= $user_id;
        $saveItem->address= $data['address'];
        $saveItem->street= $data['street'];
        $saveItem->city_name= $data['city_name'];
        $saveItem->state= $data['state'];
        $saveItem->postal_code= $data['postal_code'];
        $saveItem->default_status= 1;
        $saveItem->status= 1;
        $saveItem->save();

        if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $saveItem->id; $message='Address added successfully'; }


        $updateItem = User::where('id', '=',$user_id)->update(['address_id'=> $get_id]);
        if(!$updateItem){   $success_profile=false; }  else{   $success_profile=true; }

        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        return response()->json($response, 200);

    }
    public function edit(Request $request){


        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0|max:999999999999999999',
            'address' => 'required|string|min:5|max:200',
            'street' => 'required|string|min:2|max:200',
            'city_name' => 'required|string|min:2|max:100',
            'state' => 'required|string|min:3|max:100',
            'postal_code' => 'required|integer|min:0|max:9999999',
            
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

            $data= $request->all();

            $updateItem = User_address_list::where('id', '=',$data['id'])->update(['user_id'=> $user_id,'address'=> $data['address'],
            'street'=> $data['street'],'city_name'=> $data['city_name'],'state'=> $data['state']
                ,'postal_code'=> $data['postal_code']]);
        
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Address updated successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);


        
    }

    public function delete(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:999999999999',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();

        $updateItem = User_address_list::where('id', '=',$data['id'])->where('user_id', '=',$user_id)->delete();
    
        if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $data['id']; $message='Item deleted successfully'; }

        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        return response()->json($response, 200);

        
    }


    public function setDefault(Request $request){


            $user_id = auth('sanctum')->user()->id;

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|min:0|max:999999999999999999',
                
                ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

            $data= $request->all();

            $updateItem = User::where('id', '=',$user_id)->update(['address_id'=> $data['id']]);
        
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Default address updated successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);


        
    }


    public function venderAddressSetup(Request $request){

        $user_id = auth('sanctum')->user()->id;
        // $user_id=23;


        $findAddressStaus = User::where('id','=',$user_id)->where('address_id','=',0)->get()->count();


        if($findAddressStaus===1){  // Insert Address Query




        $validator = Validator::make($request->all(), [
            'address' => 'required|string|min:2|max:200',
            'street' => 'required|string|min:2|max:200',
            'city_name' => 'required|string|min:2|max:100',
            'state' => 'required|string|min:2|max:100',
            'postal_code' => 'required|integer|min:0|max:9999999',

            
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $data= $request->all();
        $saveItem = new User_address_list;
        $saveItem->user_id= $user_id;
        $saveItem->address= $data['address'];
        $saveItem->street= $data['street'];
        $saveItem->city_name= $data['city_name'];
        $saveItem->state= $data['state'];
        $saveItem->postal_code= $data['postal_code'];
        $saveItem->default_status= 1;
        $saveItem->status= 1;
        $saveItem->save();

        // return $saveItem;


        if(!$saveItem){   $success=false;   $get_id = 0; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $saveItem->id; $message='Address added successfully'; }

        $updateItem = User::where('id', '=',$user_id)->update(['address_id'=> $get_id]);

        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        return response()->json($response, 200);

        } else { // Update Address Query


            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|min:0|max:999999999999999999',
                'address' => 'required|string|min:5|max:200',
                'street' => 'required|string|min:2|max:200',
                'city_name' => 'required|string|min:2|max:100',
                'state' => 'required|string|min:3|max:100',
                'postal_code' => 'required|integer|min:0|max:9999999',
                
                ]);
    
                    if($validator->fails()){
                        return $this->sendError('Validation Error.', $validator->errors());       
                    }
    
                $data= $request->all();
    
                $updateItem = User_address_list::where('id', '=',$data['id'])->update(['user_id'=> $user_id,'address'=> $data['address'],
                'street'=> $data['street'],'city_name'=> $data['city_name'],'state'=> $data['state']
                    ,'postal_code'=> $data['postal_code']]);
            
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $data['id']; $message='Address updated successfully'; }
    
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
}