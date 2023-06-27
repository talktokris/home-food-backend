<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class CustomerController extends Controller
{
    public function profileUpdate(Request $request){


        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:2|max:200',
            'last_name' => 'required|string|min:2|max:200',
            'email' => 'required|email|unique:users',
            
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

            $data= $request->all();

            $updateItem = User::where('id', '=',$user_id)->update(['first_name'=> $data['first_name'],'last_name'=> $data['last_name'],
            'email'=> $data['email']]);
        
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Profile updated successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);
        }



        
        public function passwordChange(Request $request){


                $user_id = auth('sanctum')->user()->id;
    
                $validator = Validator::make($request->all(), [
                    'password' => 'required|string|min:6|max:50',
                    'c_password' => 'required|same:password',
                    
                    ]);
    
                    if($validator->fails()){
                        return $this->sendError('Validation Error.', $validator->errors());       
                    }
    
                $data= $request->all();
    
                $updateItem = User::where('id', '=',$user_id)->update(['password'=> bcrypt($data['password'])]);
            
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $data['id']; $message='Password change successfully'; }
    
                $response = [
                    'success' => $success,
                    'data'    => $get_id,
                    'message' => $message,
                ];
                return response()->json($response, 200);
    
    
            
        }

        public function radiusUpdate(Request $request){


                $user_id = auth('sanctum')->user()->id;
    
                $validator = Validator::make($request->all(), [
                    'radius' => 'required|integer|min:0|max:999',
                    
                    ]);
    
                    if($validator->fails()){
                        return $this->sendError('Validation Error.', $validator->errors());       
                    }
    
                $data= $request->all();
    
                $updateItem = User::where('id', '=',$user_id)->update(['search_radius'=> $data['radius']]);
            
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $data['id']; $message='Search radius updated successfully'; }
    
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