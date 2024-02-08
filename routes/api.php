<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\StatusFillsController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\FoodMenuController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VenderController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\MessageController;





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

    // Client Auth Api Routes
    Route::post('client-register', 'clientRegisterEmail');
    Route::post('client-login', 'clientLoginEmail');
    Route::post('client-otp-login', 'clientOtpLogin');
    Route::post('client-otp-request', 'clientOtpRequest');

    //Vender Auth Api Routes
    
    Route::post('vender-register', 'venderRegisterEmail');
    Route::post('vender-login', 'venderLoginEmail');
    Route::post('vender-otp-login', 'venderOtpLogin');
    Route::post('vender-otp-request', 'venderOtpRequest');


    //Delivery Auth Api Routes
    
    Route::post('delivery-register', 'deliveryRegisterEmail');
    Route::post('delivery-login', 'deliveryLoginEmail');
    Route::post('delivery-otp-login', 'deliveryOtpLogin');
    Route::post('delivery-otp-request', 'deliveryOtpRequest');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

    
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/profile-info', [ProfileController::class,'profile'])->name('profile');

   // Vender Api Routes

    Route::post('/vender-menu-store', [FoodMenuController::class,'store'])->name('vender-menu-store');
    Route::post('/vender-menu-edit', [FoodMenuController::class,'edit'])->name('vender-menu-edit');
    Route::post('/vender-menu-delete', [FoodMenuController::class,'delete'])->name('vender-menu-delete');
    Route::post('/vender-menu-image-upload', [FoodMenuController::class,'imageUpload'])->name('vender-menu-image-upload');
    Route::post('/vender-menu-image-delete', [FoodMenuController::class,'imageDelete'])->name('vender-menu-image-delete');
    Route::post('/vender-menu-set-default-image', [FoodMenuController::class,'imageSetDefault'])->name('vender-menu-set-default-image');
    Route::post('/vender-profile-image-upload', [CustomerController::class,'imageUpload'])->name('vender-menu-image-upload');
    Route::post('/vender-profile-image-delete', [CustomerController::class,'imageDelete'])->name('vender-menu-image-delete');
   
    Route::post('/vender-menu-heading-store', [FoodMenuController::class,'headingStore'])->name('vender-menu-heading-store');
    Route::post('/vender-menu-heading-edit', [FoodMenuController::class,'headingEdit'])->name('vender-menu-heading-edit');
    Route::post('/vender-menu-heading-delete', [FoodMenuController::class,'headingDelete'])->name('vender-menu-heading-delete');

    Route::post('/vender-menu-addon-store', [FoodMenuController::class,'addOnStore'])->name('vender-menu-addon-store');
    Route::post('/vender-menu-addon-edit', [FoodMenuController::class,'addOnEdit'])->name('vender-menu-addon-edit');
    Route::post('/vender-menu-addon-delete', [FoodMenuController::class,'addOneDelete'])->name('vender-menu-addon-delete');

    Route::post('/vender-menu-fetch-all', [FoodMenuController::class,'fetchAllItems'])->name('vender-menu-fetch-all');
    Route::post('/vender-menu-informations', [FoodMenuController::class,'fetchVenderInfo'])->name('vender-menu-fetch-info');
    Route::post('/vender-menu-fetch-single', [FoodMenuController::class,'venderFetchSingleMenu'])->name('vender-menu-fetch-single');
    
     // Client Api Routes

    Route::post('/client-food-search', [FoodMenuController::class,'searchFoods'])->name('client-food-search-all');
    Route::post('/client-menu-fetch-single', [FoodMenuController::class,'clientFetchVenderMenu'])->name('client-fetch-vender-single');

    Route::post('/client-food-home', [FoodMenuController::class,'homeFoods'])->name('client-food-home');


    Route::post('/client-address-store', [AddressController::class,'store'])->name('client-address-store');
    Route::post('/client-address-edit', [AddressController::class,'edit'])->name('client-address-edit');
    Route::post('/client-address-delete', [AddressController::class,'delete'])->name('client-address-delete');
    Route::post('/client-address-setdefault', [AddressController::class,'setDefault'])->name('client-address-default');
    Route::post('/vender-address-setup', [AddressController::class,'venderAddressSetup'])->name('vender-address-setup');
   
    
    Route::post('/client-profile-update', [CustomerController::class,'profileUpdate'])->name('client-profile-update');
    Route::post('/client-change-password', [CustomerController::class,'passwordChange'])->name('client-change-password');
    Route::post('/client-set-search-radius', [CustomerController::class,'radiusUpdate'])->name('client-set-search-radius');
    Route::post('/client-message', [MessageController::class,'clientMessage'])->name('client-messages');


    Route::post('/vender-profile-update', [VenderController::class,'profileUpdate'])->name('vender-profile-update');
    Route::post('/vender-change-password', [VenderController::class,'passwordChange'])->name('vender-change-password');
    Route::post('/vender-set-search-radius', [VenderController::class,'radiusUpdate'])->name('vender-set-search-radius');
    Route::post('/vender-message', [MessageController::class,'venderMessage'])->name('vender-messages');

    Route::post('/client-order-store', [OrderController::class,'clientStore'])->name('client-order-store');
    Route::post('/client-order-pending', [OrderController::class,'clientPending'])->name('client-order-panding');
    Route::post('/client-order-histroy', [OrderController::class,'clientOrderHistory'])->name('client-order-history');

    Route::post('/vender-order-pending', [OrderController::class,'venderOrdersPending'])->name('vender-order-status');
    Route::post('/vender-order-running', [OrderController::class,'venderOrdersRunning'])->name('vender-order-status');
    Route::post('/vender-order-ready-to-deliver', [OrderController::class,'venderOrdersReadyForDelivery'])->name('vender-order-ready-for-delivery');
    Route::post('/vender-sales-change-status', [OrderController::class,'venderSalesStatus'])->name('vender-sales-change-status');
    
    // Route::post('/vender-order-complited-history', [OrderController::class,'venderOrdersComplitedHistory'])->name('vender-order-history');
   
    
    Route::post('/vender-order-status-change', [OrderController::class,'venderChangeStatus'])->name('vender-order-status-change');

    // Delivery Api Routes
    
    Route::post('/delivery-pending-orders', [DeliveryController::class,'deliveryOrdersPending'])->name('delivery-pending-orders');
    Route::post('/delivery-on-the-way', [DeliveryController::class,'deliveryOnTheWay'])->name('delivery-on-the-way');
   
    Route::post('/delivery-order-complited-history', [DeliveryController::class,'deliveryOrdersComplitedHistory'])->name('delivery-order-complited-history');
    Route::post('/delivery-order-status-change', [DeliveryController::class,'deliveryChangeStatus'])->name('delivery-order-status-change');
    
    

   // Route::get('/staus-message', [StatusFillsController::class, 'status']);
   // Route::get('/users-role', [StatusFillsController::class, 'userRole']);
});

// Comman Setting Routes

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