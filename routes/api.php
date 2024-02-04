<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\HotelController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\BookingController;

use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\API\HotelSearchController;
use App\Http\Controllers\PhoneNumberVerificationController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user', [UserController::class,'create']);
Route::get('user', [UserController::class,'show']);

Route::post('verifyotp', [AuthController::class,'verifyotp']);
Route::post('login', [AuthController::class, 'login']);
Route::post('updateprofile/{id}', [AuthController::class, 'updateprofile']);
Route::post('uploadImage',[Authcontroller::class,'uploadImage']);
Route::post('emaillogin', [AuthController::class, 'emaillogin']);
Route::post('uploadImage', [AuthController::class, 'uploadImage']);
Route::post('socaillogin', [AuthController::class, 'socaillogin']);


 // List all hotels
 Route::get('/hotels', [HotelController::class, 'index']);

 // Retrieve a single hotel by ID
 Route::get('/hotels/{id}', [HotelController::class, 'show']);

 // Create a new hotel
 Route::post('/hotels', [HotelController::class, 'store']);

 // Update a hotel by ID
 Route::put('/hotels/{id}', [HotelController::class, 'update']);

 // Delete a hotel by ID
 Route::delete('/hotels/{id}', [HotelController::class, 'destroy']);



 Route::get('/hotel/search', [HotelSearchController::class, 'search']);




// Route::post('/hello',function(Request $request){
//     return response()->json(['message' => 'Hello World'], 201);
// });



// Switch between user and host roles
// Route::post('switch-to-host', [AuthController::class, 'switchToHost']);
// Route::post('/user/switch-to-user', [AuthController::class, 'switchToUser']);

Route::post('/changemode', [AuthController::class,'changemode']);


Route::post('store',[BookingController::class,'store']);
Route::get('index',[BookingController::class,'index']);
Route::put('bookings/{id}/accept', [BookingController::class, 'accept']);
Route::put('bookings/{id}/cancel', [BookingController::class, 'cancel']);


Route::post('charge', [PaymentController::class,'charge']);
Route::post('payments/verify',[PaymentController::class,'verify']);
Route::get('users/{userId}/payments', [PaymentController::class,'listPayments']);



Route::post('reviews', [ReviewController::class,'store']);
Route::put('reviews/{id}', [ReviewController::class,'update']);
Route::delete('reviews/{id}', [ReviewController::class,'destroy']);
Route::get('hotels/{hotel_id}/reviews', [ReviewController::class,'index']);



Route::post('wishlist/add-hotel', [WishlistController::class,'addHotelToWishlist']);
Route::get('wishlists', [WishlistController::class,'index']);
Route::delete('wishlist/remove-hotel', [WishlistController::class,'removeHotelFromWishlist']);
Route::put('wishlist/{wishlist}', [WishlistController::class, 'update']);









// Route::post('send-verification-code', [PhoneNumberVerificationController::class, 'sendVerificationCode']);
// Route::post('verify-phone-number', [PhoneNumberVerificationController::class, 'verifyPhoneNumber']);

