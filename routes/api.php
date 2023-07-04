<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\StatusFillsController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\FoodMenuController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::controller(RegisterController::class)->group(function(){
    Route::post('client-register', 'clientRegisterEmail');
    Route::post('client-login', 'clientLoginEmail');
    Route::post('client-otp-login', 'clientOtpLogin');
    Route::post('client-otp-request', 'clientOtpRequest');
    Route::post('vender-register', 'venderRegisterEmail');
    Route::post('vender-login', 'venderLoginEmail');
    Route::post('vender-otp-login', 'venderOtpLogin');
    Route::post('vender-otp-request', 'venderOtpRequest');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

    
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profile-info', [ProfileController::class,'profile'])->name('profile');


    Route::post('/vender-menu-store', [FoodMenuController::class,'store'])->name('vender-menu-store');
    Route::post('/vender-menu-edit', [FoodMenuController::class,'edit'])->name('vender-menu-edit');
    Route::post('/vender-menu-delete', [FoodMenuController::class,'delete'])->name('vender-menu-delete');
    Route::post('/vender-menu-image-upload', [FoodMenuController::class,'imageUpload'])->name('vender-menu-image-upload');
    Route::post('/vender-menu-image-delete', [FoodMenuController::class,'imageDelete'])->name('vender-menu-image-delete');
    Route::post('/vender-menu-set-default-image', [FoodMenuController::class,'imageSetDefault'])->name('vender-menu-set-default-image');
    Route::post('/vender-menu-fetch-all', [FoodMenuController::class,'fetchAllItems'])->name('vender-menu-fetch-all');
    Route::post('/vender-menu-fetch-single', [FoodMenuController::class,'fetchSingleItem'])->name('vender-menu-fetch-single');

    Route::post('/client-food-search', [FoodMenuController::class,'searchFoods'])->name('client-food-search-all');
    Route::post('/client-food-home', [FoodMenuController::class,'homeFoods'])->name('client-food-home');


    Route::post('/client-address-store', [AddressController::class,'store'])->name('client-address-store');
    Route::post('/client-address-edit', [AddressController::class,'edit'])->name('client-address-edit');
    Route::post('/client-address-delete', [AddressController::class,'delete'])->name('client-address-delete');
    Route::post('/client-address-setdefault', [AddressController::class,'setDefault'])->name('client-address-default');
    Route::post('/vender-address-setup', [AddressController::class,'venderAddressSetup'])->name('vender-address-setup');
   
    
    Route::post('/client-profile-update', [CustomerController::class,'profileUpdate'])->name('client-profile-update');
    Route::post('/client-change-password', [CustomerController::class,'passwordChange'])->name('client-change-password');
    Route::post('/client-set-search-radius', [CustomerController::class,'radiusUpdate'])->name('client-set-search-radius');

    Route::post('/client-order-store', [OrderController::class,'clientStore'])->name('client-order-store');
    Route::post('/client-order-pending', [OrderController::class,'clientPending'])->name('client-order-panding');
    Route::post('/client-order-histroy', [OrderController::class,'clientOrderHistory'])->name('client-order-history');

    Route::post('/vender-order-records', [OrderController::class,'venderOrdersStatus'])->name('vender-order-status');
    Route::post('/vender-order-running', [OrderController::class,'venderOrdersRunning'])->name('vender-order-status');
    Route::post('/vender-order-ready-to-deliver', [OrderController::class,'venderOrdersReadyForDelivery'])->name('vender-order-ready-for-delivery');
    Route::post('/vender-order-complited-history', [OrderController::class,'venderOrdersComplitedHistory'])->name('vender-order-history');
   
    
    Route::post('/vender-order-status-change', [OrderController::class,'venderChangeStatus'])->name('vender-order-status-change');

    

    
    

   // Route::get('/staus-message', [StatusFillsController::class, 'status']);
   // Route::get('/users-role', [StatusFillsController::class, 'userRole']);
});

Route::get('/status-message', [StatusFillsController::class, 'status']);
Route::get('/users-role', [StatusFillsController::class, 'userRole']);


/*
Route::middleware('auth:sanctum')->group(function () {
  //  Route::post('/logout', LogoutAction::class)->name('auth.logout');

    Route::get('/staus-message', [StatusFillsController::class, 'status']);
    Route::get('/users-role', [StatusFillsController::class, 'userRole']);
});

/*
Route::get('/staus-message', [StatusFillsController::class, 'status']);
Route::get('/users-role', [StatusFillsController::class, 'userRole']);


/*

Route::controller(RegisterController::class)->group(function(){

    Route::post('register', 'register');

    Route::post('login', 'login');

});

*/