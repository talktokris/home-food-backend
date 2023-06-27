<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food_menu;
use App\Models\Food_menu_image;
use Illuminate\Support\Facades\Auth;
use Validator;
use Intervention\Image\Facades\Image;
use App\Http\Resources\FoodMenuResource;

    class FoodMenuController extends Controller
    {
    

    public function store(Request $request){

            $user_id = auth('sanctum')->user()->id;

            $validator = Validator::make($request->all(), [
                'food_title' => 'required|string|min:5|max:100',
                'food_description' => 'required|string|min:5|max:200',
                'veg_status' => 'required|integer|min:0|max:10',
                'vender_price' => 'required|integer|min:1|max:9999',
                'customer_price' => 'required|integer|min:1|max:9999',
                'discount_per' => 'required|integer|min:0|max:100',
                'active_status' => 'required|integer|min:0|max:10',

                
                ]);
    
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $data= $request->all();
            $saveItem = new Food_menu;
            $saveItem->user_id= $user_id;
            $saveItem->food_title= $data['food_title'];
            $saveItem->food_description= $data['food_description'];
            $saveItem->veg_status= $data['veg_status'];
            $saveItem->vender_price= $data['vender_price'];
            $saveItem->customer_price= $data['customer_price'];
            $saveItem->discount_per= $data['discount_per'];
            $saveItem->active_status= 1;
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $message='Item added successfully'; }

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
            'id' => 'required|integer|min:1|max:999999999999',
            'food_title' => 'required|string|min:5|max:100',
            'food_description' => 'required|string|min:5|max:200',
            'veg_status' => 'required|integer|min:0|max:10',
            'vender_price' => 'required|integer|min:1|max:9999',
            'customer_price' => 'required|integer|min:1|max:9999',
            'discount_per' => 'required|integer|min:0|max:100',
            'active_status' => 'required|integer|min:0|max:10',
            
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

            $data= $request->all();

            $updateItem = Food_menu::where('id', '=',$data['id'])->update(['food_title'=> $data['food_title'],'food_description'=> $data['food_description'],'veg_status'=> $data['veg_status'],'vender_price'=> $data['vender_price'],'customer_price'=> $data['customer_price']
                ,'discount_per'=> $data['discount_per'],'active_status'=> $data['active_status']]);
        
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Item updated successfully'; }

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

        $updateItem = Food_menu::where('id', '=',$data['id'])->delete();;
    
        if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $data['id']; $message='Item deleted successfully'; }

        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        return response()->json($response, 200);

    
    }

    public function imageUpload(Request $request){

        // return "Hi Krishna";
        //  $data= $request->all();

        //  return $data;

        $user_id = auth('sanctum')->user()->id;

        $savingPath='vender_images/menu';

        $validator = Validator::make($request->all(), [
            'image_name'=>'required|mimes:png,jpg,gif,jpeg|max:8048',
            'menu_id' => 'required|integer|min:1|max:999999999999',
            ]);
            $data= $request->all();
            $imageName=$data['image_name'];

        $menu_id=$data['menu_id'];
        $maxOriginalNameSize=50;
        //   if(strlen($ImageNameOrg) > $maxOriginalNameSize){ $ImageNewNameSet=substr($ImageNameOrg, -5, $maxOriginalNameSize);
        //       $ImageNewName= $ImageNewNameSet.'.'.$imageName->getClientOriginalExtension();
        //    }
        //   else { $ImageNewName = $ImageNameOrg;}// shorting the image name;

        // $imageNewName =  strtotime('Y-m-d H:i:s');

        $getImageName = strtotime(date("Y-m-d H:i:s.u")).'.'.$imageName->getClientOriginalExtension();

        // return $getImageName;




        $newPath= $savingPath.'/'.$menu_id;

        if (!file_exists($newPath)) {  mkdir($newPath, 0777, true);  }

        $img = Image::make($imageName)->fit(800, 600, function ($constraint) {
                $constraint->upsize();
        });
        $upload = $img->save($newPath.'/'.$getImageName, 60);

        if($upload){
            $saveItem = new Food_menu_image;
            $saveItem->food_menu_id= $data['menu_id'];
            $saveItem->image_name= $getImageName;
            $saveItem->save();
            $upImageID = $saveItem->id;

            $imageSave = Food_menu::where("id", $data['menu_id'])->update(["menu_profile_img_id" => $upImageID]);
        
        }

        if(!$upload){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
        else{   $success=true; $get_id = $upImageID; $message='Image uploaded successfully'; }

        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        return response()->json($response, 200);

        
    }

    public function imageDelete(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'image_id' => 'required|integer|min:1|max:999999999999',
            'menu_id' => 'required|integer|min:1|max:999999999999',
            ]);
            $data= $request->all();
            $image_id=$data['image_id'];
            $menu_id=$data['menu_id'];

        $deleteImagData= Food_menu_image::where('id','=', $image_id)->get();

        if($image_id!=''){

            $img_location= 'vender_images/menu/'.$menu_id;

            foreach($deleteImagData as $dRow){

                
                if($dRow->image_name){
                    $imgDelNmOne = $img_location.'/'.$dRow->image_name;
                    if (file_exists($imgDelNmOne)) { unlink($imgDelNmOne);  }
                }
            // 


            }

            $delete = Food_menu_image::where('id', '=', $image_id)->delete();

            if(!$delete){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $menu_id; $message='Image deleted successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);

        }
    }
    

    public function imageSetDefault(Request $request){

            $user_id = auth('sanctum')->user()->id;

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|min:1|max:999999999999',
                'img_id' => 'required|integer|min:1|max:999999999999',

                
                ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

            $data= $request->all();

            $updateItem = Food_menu::where('id', '=',$data['id'])->update(['menu_profile_img_id'=> $data['img_id']]);
        
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Image default set successfully'; }

            $response = [
                'success' => $success,
                'data'    => $get_id,
                'message' => $message,
            ];
            return response()->json($response, 200);



        
    }

    public function fetchAllItems(Request $Request){
        
        $user_id = auth('sanctum')->user()->id;
        $menuData= Food_menu::where('user_id', '=', $user_id)->get();
        // 'results' => UserProfileResource::collection($user_info)

        $response = [
            'success' => true,
            'results' => FoodMenuResource::collection($menuData),
        ];
        return response()->json($response, 200);
        
    }

    public function fetchSingleItem(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:999999999999',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();
        
        $menuData = Food_menu::where('id', '=', $data['id'])->get();
        // 'results' => UserProfileResource::collection($user_info)

        $response = [
            'success' => false,
            'results' => FoodMenuResource::collection($menuData),
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




        public function searchFoods(Request $Request){
            
            $user_id = auth('sanctum')->user()->id;
            $menuData= Food_menu::where('active_status', '=', 1)->orderBy('id', 'DESC')->get();
            // 'results' => UserProfileResource::collection($user_info)

            $response = [
                'success' => true,
                'results' => FoodMenuResource::collection($menuData),
            ];
            return response()->json($response, 200);
        }

    

    public function homeFoods(Request $Request){
            
        $user_id = auth('sanctum')->user()->id;
        $menuData= Food_menu::where('active_status', '=', 1)->orderBy('id', 'DESC')->limit(5)->get();
        // 'results' => UserProfileResource::collection($user_info)

        $response = [
            'success' => true,
            'results' => FoodMenuResource::collection($menuData),
        ];
        return response()->json($response, 200);
    }
        
    }

    
    