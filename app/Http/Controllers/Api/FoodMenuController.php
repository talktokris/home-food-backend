<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Food_menu;
use App\Models\Food_menu_image;
use App\Models\Home_page_slide;
use App\Models\Food_menu_argument_heading;
use App\Models\Food_menu_argument_item;

use Illuminate\Support\Facades\Auth;
use Validator;
use Intervention\Image\Facades\Image;
use App\Http\Resources\FoodMenuResource;
use App\Http\Resources\FoodMenuVenderResource;
use App\Http\Resources\HomeFoodResource;
use App\Http\Resources\MenuVenderResource;
use App\Http\Resources\SalesOrderResource;



    class FoodMenuController extends Controller
    {

    public function addOnStore(Request $request){


        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'food_menu_id' => 'required|integer|min:1|max:9999999999999999',
            'heading_id' => 'required|integer|min:1|max:9999999999999999',
            'description' => 'required|string|min:2|max:2000',
            'vender_price' => 'required|between:0,999999.99',
            'price' => 'required|between:0,999999.99',
            'status' => 'required|integer|min:0|max:100',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();

        $foodVenderCount = Food_menu:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();

        if($foodVenderCount>=1){
            // return $foodVenderCount;

            $vender_price = number_format((float)$data['vender_price'], 2, '.', ''); 
         
            // $vender_price = number_format($number, 2, ',', ' ');
            $price = number_format((float)$data['price'], 2, '.', ''); 
            $saveItem = new Food_menu_argument_item;
            $saveItem->food_menu_id= $data['food_menu_id'];
            $saveItem->heading_id= $data['heading_id'];
            $saveItem->description= $data['description'];
            $saveItem->vender_price= $vender_price;
            $saveItem->price= $price;
            $saveItem->status= $data['status'];
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $message='Item added successfully'; }

        } else {
                $success=false;
                $get_id=$foodVenderCount;
                $message='Unauthorized action'; 
        }
        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    
    }

    public function addOnEdit(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'food_menu_id' => 'required|integer|min:1|max:9999999999999999',
            'heading_id' => 'required|integer|min:1|max:9999999999999999',
            'description' => 'required|string|min:2|max:2000',
            'vender_price' => 'required|between:0,999999.99',
            'price' => 'required|between:0,999999.99',
            'status' => 'required|integer|min:0|max:100',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        
        $foodVenderCount = Food_menu:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();

        if($foodVenderCount>=1){
            $vender_price = number_format((float)$data['vender_price'], 2, '.', ''); 
            // $vender_price = number_format($number, 2, ',', ' ');
            $price = number_format((float)$data['price'], 2, '.', ''); 

            $updateItem = Food_menu_argument_item::where('id', '=',$data['id'])->update(['description'=> $data['description'],'vender_price'=> $data['vender_price'],'price'=> $data['price'],'status'=> $data['status']]);

            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Item updated successfully'; }

        } else {
                $success=false;
                $get_id=$foodVenderCount;
                $message='Unauthorized action'; 
        }
        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);

    
    }

    public function addOneDelete(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'food_menu_id' => 'required|integer|min:1|max:9999999999999999',
        ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();


        $foodVenderCount = Food_menu:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();

        if($foodVenderCount>=1){
            // return $foodVenderCount;
            // $updateItem = Food_menu_argument_item::where('id', '=',$data['id'])->delete();

            $updateItem = Food_menu_argument_item::where("id", $data['id'])->update(["delete_status" => 1]);
    
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Item deleted successfully'; }
    
        } else {
                $success=false;
                $get_id=$foodVenderCount;
                $message='Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        return response()->json($response, 200);

    
    }     

    public function headingStore(Request $request){
    
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'food_menu_id' => 'required|integer|min:1|max:9999999999999999',
            'title' => 'required|string|min:2|max:2000',
            'pick_type' => 'required|integer|min:0|max:10',
            'status' => 'required|integer|min:0|max:100',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();

        $foodVenderCount = Food_menu:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();

        if($foodVenderCount>=1){
            // return $foodVenderCount;

            $saveItem = new Food_menu_argument_heading;
            $saveItem->food_menu_id= $data['food_menu_id'];
            $saveItem->title= $data['title'];
            $saveItem->pick_type= $data['pick_type'];
            $saveItem->status= $data['status'];
            $saveItem->save();

            if(!$saveItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $saveItem->id; $message='Title added successfully'; }

        } else {
                $success=false;
                $get_id=$foodVenderCount;
                $message='Unauthorized action'; 
        }


        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }

    public function headingEdit(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'food_menu_id' => 'required|integer|min:1|max:9999999999999999',
            'title' => 'required|string|min:2|max:2000',
            'pick_type' => 'required|integer|min:0|max:10',
            'status' => 'required|integer|min:0|max:100',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $data= $request->all();
        
        $foodVenderCount = Food_menu:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();

        if($foodVenderCount>=1){
            // return $foodVenderCount;

            $updateItem = Food_menu_argument_heading::where('id', '=',$data['id'])->update(['title'=> $data['title'],'pick_type'=> $data['pick_type'],'status'=> $data['status']]);
    

            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Title updated successfully'; }

        } else {
                $success=false;
                $get_id=$foodVenderCount;
                $message='Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        
        return response()->json($response, 200);

    
    }

    public function headingDelete(Request $request){
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:9999999999999999',
            'food_menu_id' => 'required|integer|min:1|max:9999999999999999',
        ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();


        $foodVenderCount = Food_menu:: where([['id','=',$data['food_menu_id']], ['user_id','=',$user_id]])->get()->count();

        if($foodVenderCount>=1){
            // return $foodVenderCount;
            // $updateItem = Food_menu_argument_heading::where('id', '=',$data['id'])->delete();;

            $updateItem = Food_menu_argument_heading::where("id", $data['id'])->update(["delete_status" => 1]);
    
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Title deleted successfully'; }
    
        } else {
                $success=false;
                $get_id=$foodVenderCount;
                $message='Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        return response()->json($response, 200);

    
    }
    

    public function store(Request $request){

            $user_id = auth('sanctum')->user()->id;

            $validator = Validator::make($request->all(), [
                'food_title' => 'required|string|min:2|max:100',
                'food_description' => 'required|string|min:5|max:2000',
                'food_category' => 'required|string|min:2|max:2000',
                'food_category_id' => 'required|integer|min:0|max:1000',
                'veg_status' => 'required|integer|min:0|max:100',
                'halal_status' => 'required|integer|min:0|max:100',
                'vender_price' => 'required|integer|min:1|max:9999',
                'customer_price' => 'required|integer|min:1|max:9999',
                'discount_per' => 'required|integer|min:0|max:100',
                'active_status' => 'required|integer|min:0|max:100',

                
                ]);
    
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $data= $request->all();
            $saveItem = new Food_menu;
            $saveItem->user_id= $user_id;
            $saveItem->food_title= $data['food_title'];
            $saveItem->food_description= $data['food_description'];
            $saveItem->food_category= $data['food_category'];
            $saveItem->food_category_id= $data['food_category_id'];
            $saveItem->veg_status= $data['veg_status'];
            $saveItem->halal_status= $data['halal_status'];
            $saveItem->vender_price= $data['vender_price'];
            $saveItem->customer_price= $data['customer_price'];
            $saveItem->discount_per= $data['discount_per'];
            $saveItem->active_status= $data['active_status'];
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
            'id' => 'required|integer|min:1|max:999999999',
            'food_title' => 'required|string|min:2|max:100',
            'food_description' => 'required|string|min:5|max:2000',
            'food_category' => 'required|string|min:2|max:2000',
            'food_category_id' => 'required|integer|min:0|max:1000',
            'veg_status' => 'required|integer|min:0|max:100',
            'halal_status' => 'required|integer|min:0|max:100',
            'vender_price' => 'required|integer|min:1|max:9999',
            'customer_price' => 'required|integer|min:1|max:9999',
            'discount_per' => 'required|integer|min:0|max:100',
            'active_status' => 'required|integer|min:0|max:100',
            
            ]);

                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }

            $data= $request->all();

            $foodVenderCount = Food_menu:: where([['id','=',$data['id']], ['user_id','=',$user_id]])->get()->count();

            if($foodVenderCount>=1){

                $updateItem = Food_menu::where('user_id', '=',$user_id)->where('id', '=',$data['id'])->update(['food_title'=> $data['food_title'],'food_description'=> $data['food_description'],'food_category'=> $data['food_category'],'food_category_id'=> $data['food_category_id'],'veg_status'=> $data['veg_status'],'halal_status'=> $data['halal_status'],'vender_price'=> $data['vender_price'],'customer_price'=> $data['customer_price']
                ,'discount_per'=> $data['discount_per'],'active_status'=> $data['active_status']]);
        
                if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $data['id']; $message='Item updated successfully'; }


            } else {
                    $success=false;
                    $get_id=$foodVenderCount;
                    $message='Unauthorized action'; 
            }    
    
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

        $foodVenderCount = Food_menu:: where([['id','=',$data['id']], ['user_id','=',$user_id]])->get()->count();

        if($foodVenderCount>=1){

            // $updateItem = Food_menu::where('id', '=',$data['id'])->delete();;
            $updateItem = Food_menu::where("id", $data['id'])->update(["delete_status" => 1]);
    
            if(!$updateItem){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
            else{   $success=true; $get_id = $data['id']; $message='Item deleted successfully'; }

        } else {
                $success=false;
                $get_id=$foodVenderCount;
                $message='Unauthorized action'; 
        }

        $response = [
            'success' => $success,
            'data'    => $get_id,
            'message' => $message,
        ];
        return response()->json($response, 200);

    
    }

    public function imageUpload(Request $request){


        $user_id = auth('sanctum')->user()->id;
        

        $savingPath='vender_images/venders';

        $validator = Validator::make($request->all(), [
            'image_name'=>'required|mimes:png,jpg,gif,jpeg|max:8048',
            'menu_id' => 'required|integer|min:1|max:999999999999',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


            $data= $request->all();
            $imageName=$data['image_name'];

            // return $user_id;

            $foodVenderCount = Food_menu:: where([['id','=',$data['menu_id']], ['user_id','=',$user_id]])->get()->count();

            if($foodVenderCount>=1){
 
                $menu_id=$data['menu_id'];
                $maxOriginalNameSize=50;
                //   if(strlen($ImageNameOrg) > $maxOriginalNameSize){ $ImageNewNameSet=substr($ImageNameOrg, -5, $maxOriginalNameSize);
                //       $ImageNewName= $ImageNewNameSet.'.'.$imageName->getClientOriginalExtension();
                //    }
                //   else { $ImageNewName = $ImageNameOrg;}// shorting the image name;
        
                // $imageNewName =  strtotime('Y-m-d H:i:s');
        
                $getImageName = strtotime(date("Y-m-d H:i:s.u")).'.'.$imageName->getClientOriginalExtension();
        
                // return $getImageName;
      
                $newPath= $savingPath.'/'.$user_id;
        
                if (!file_exists($newPath)) {  mkdir($newPath, 0777, true);  }
        
                $img = Image::make($imageName)->fit(900, 600, function ($constraint) {
                        $constraint->upsize();
                });
                $upload = $img->save($newPath.'/'.$getImageName, 60);
        
                if($upload){
                    $imageSave = Food_menu::where("id", $data['menu_id'])->update(["image_name" => $getImageName]);
                }
        
                if(!$upload){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                else{   $success=true; $get_id = $menu_id; $message='Image uploaded successfully'; }

            } else {
                    $success=false;
                    $get_id=$foodVenderCount;
                    $message='Unauthorized action'; 
            }      

        $response = [
            'success' => $success,
            'data'    => $get_id.'-'.$user_id,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function imageDelete(Request $request){

        $user_id = auth('sanctum')->user()->id;
       
        $validator = Validator::make($request->all(), [
            'menu_id' => 'required|integer|min:1|max:999999999999',
            'image_name' => 'required|string|min:2|max:100',
            ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
            $data= $request->all();
            $menu_id=$data['menu_id'];
            $image_name=$data['image_name'];
      
            $foodVenderCount = Food_menu:: where([['id','=',$data['menu_id']], ['user_id','=',$user_id]])->get()->count();
            if($foodVenderCount>=1){
    
                if($menu_id!=''){
                    $imageSavedPath='vender_images/venders/'.$user_id.'/'. $image_name;

                    if (file_exists($imageSavedPath)) { unlink($imageSavedPath);  }
                    $delete = Food_menu::where("id", $menu_id)->update(["image_name" => NULL]);
         
                    if(!$delete){   $success=false;   $get_id = 1; $message='Unknown Error, Plz Contact support'; }
                    else{   $success=true; $get_id = $menu_id; $message='Image deleted successfully'; }
     
                 }
        
            } else {
                    $success=false;
                    $get_id=$foodVenderCount;
                    $message='Unauthorized action'; 
            }      



        $response = [
            'success' => $success,
            'data'    => $get_id.'-'.$user_id.'-'.$image_name,
            'message' => $message,
        ];

        
        return response()->json($response, 200);


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
            'results' => FoodMenuVenderResource::collection($menuData),
            // 'results' => FoodMenuResource::collection($menuData),

            
        ];
        return response()->json($response, 200);
        
    }

    public function fetchVenderInfo(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:999999999999',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();
        
        $menuData = Food_menu::where('user_id', '=', $data['id'])->where('delete_status', '=', 0)->get();
        $venderData = User::where('id', '=', $data['id'])->get();
        // 'results' => UserProfileResource::collection($user_info)

        $response = [
            'success' => false,
            'vender'=> MenuVenderResource::collection($venderData),
            'food'=> FoodMenuVenderResource::collection($menuData),
            // 'food'=> FoodMenuResource::collection($menuData),

            
            // 'results' => FoodMenuResource::collection($menuData),
        ];
        return response()->json($response, 200);

        
    }

    public function fetchSingleMenuOld(Request $request){

        $user_id = auth('sanctum')->user()->id;

        // return $user_id;
        // $user_id=23;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:999999999999',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();
        
        $menuData = Food_menu::where('user_id', '=', $user_id)->where('id', '=', $data['id'])->where('delete_status', '=', 0)->get();
        // 'results' => UserProfileResource::collection($user_info)

        $response = [
            'success' => false,
            'food'=> FoodMenuVenderResource::collection($menuData),
            // 'food'=> FoodMenuResource::collection($menuData),

            
            // 'results' => FoodMenuResource::collection($menuData),
        ];
        return response()->json($response, 200);

        
    }


    public function venderFetchSingleMenu(Request $request){

        $user_id = auth('sanctum')->user()->id;

        // return $user_id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:999999999999',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();
        
        $menuData = Food_menu::where('id', '=', $data['id'])->get();
        $menuData = Food_menu::where('user_id', '=', $user_id)->where('id', '=', $data['id'])->where('delete_status', '=', 0)->get();
        // return $menuData;
        // $venderData = User::where('id', '=', $user_id)->get();
            // 'results' => UserProfileResource::collection($user_info)

        $response = [
            'success' => true,
            // 'vender'=> MenuVenderResource::collection($venderData),
            'food'=> FoodMenuResource::collection($menuData),
            // 'results' => FoodMenuResource::collection($menuData),
        ];
        return response()->json($response, 200);

        
    }


    public function clientFetchVenderMenu(Request $request){

        $user_id = auth('sanctum')->user()->id;

        $user_id = 23;

        //  return $user_id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|max:999999999999',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

        $data= $request->all();
        // return  $data['id'];
        
        // $menuData = Food_menu::where('id', '=', $data['id'])->get();
        // $menuData = Food_menu::where('user_id', '=', $user_id)->where('id', '=', $data['id'])->where('delete_status', '=', 0)->get();
        $menuData = Food_menu::where('user_id', '=', $user_id)->where('delete_status', '=', 0)->get();
       
        // return $menuData;
        $venderData = User::where('id', '=', $user_id)->get();
            // 'results' => UserProfileResource::collection($user_info)

        $response = [
            'success' => true,
            'vender'=> MenuVenderResource::collection($venderData),
            'food'=> FoodMenuResource::collection($menuData),
            // 'results' => FoodMenuResource::collection($menuData),
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

            $validator = Validator::make($Request->all(), [
                'search' => 'required|string|min:1|max:100',
                ]);
    
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $data= $Request->all();
            $searchWord= $data['search'];
            $escaped = '%' . $searchWord . '%';
          
           
           $menuData= Food_menu::with('venderInfo')->where('active_status', '=', 1)

                ->where(function($data) use($escaped){
                               return $data 
                               ->orWhere('food_title', 'LIKE', $escaped)
                               ->orWhere('food_category', 'LIKE', $escaped);
                        })
                  
                ->orWhereHas('venderInfo', function($q) use ($escaped) {
                        $q->where(function($q) use ($escaped) {
                            $q->where('name', 'LIKE',   $escaped );
                            $q->orWhere('first_name', 'LIKE',  $escaped );
                            $q->orWhere('last_name', 'LIKE',  $escaped );
                        });
                    })

         
                ->orderBy('food_menus.id', 'DESC')->get();

            $response = [
                'success' => true,
               // 'slides'=>$homeSlides,
               // 'rec_food'=> HomeFoodResource::collection($recMenuData),
                'top_food'=> HomeFoodResource::collection($menuData),
               // 'results' => FoodMenuResource::collection($menuData),
               // 'results2' => $menuData,
            ];
            return response()->json($response, 200);
        }

    

    public function homeFoods(Request $Request){
            
        $user_id = auth('sanctum')->user()->id;
        
        $menuData= Food_menu::where('active_status', '=', 1)->orderBy('id', 'DESC')->limit(30)->get();
        $recMenuData= Food_menu::where('active_status', '=', 1)->inRandomOrder()->limit(6)->orderBy('id', 'DESC')->get();
        $topMenuData= Food_menu::where('active_status', '=', 1)->inRandomOrder()->limit(10)->orderBy('id', 'DESC')->get();
        // 'results' => UserProfileResource::collection($user_info)
        $homeSlides = Home_page_slide::where('status','=',1)->get();
        $response = [
            'success' => true,
            'slides'=>$homeSlides,
            'rec_food'=> HomeFoodResource::collection($recMenuData),
            'top_food'=> HomeFoodResource::collection($topMenuData),
           // 'results' => FoodMenuResource::collection($menuData),
           // 'results2' => $menuData,
        ];
        return response()->json($response, 200);
    }
        
    }

    
    