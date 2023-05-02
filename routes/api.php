<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\Channel;
use App\Http\Controllers\Popular;





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

// public route

// Route::get('/products/search/{name}',[ProductController::class,'search']);
// Route::get('/products',[ProductController::class,'index']);
// Route::get('/products/{id}',[ProductController::class,'show']);
// Route::get('/products/search/{name}',[ProductController::class,'search']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/searchfriends',[SetupController::class,'searchFriend']);
Route::group(['middleware'=>['auth:sanctum']], function () {


//CHANNEL
Route::post('/searchchannel',[Channel::class,'Search_Channel']);
Route::post('/searchpodcast',[Channel::class,'Search_Podcast']);	

//popular
Route::post('/searchpopular_channel',[Popular::class,'Searchpopular_Channel']);	
Route::post('/searchpopular_episode',[Popular::class,'Searchpopular_Episode']);	



//SETUP	

Route::post('/setupstep',[SetupController::class,'Step_setup']);
Route::post('/searchfriend',[SetupController::class,'searchFriend']);
Route::post('/addfriend',[SetupController::class,'addFriend']);

Route::post('/logout',[AuthController::class,'logout']);


	
   
});





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/login/{provider}', [AuthController::class,'redirectToProvider']);
Route::get('/login/{provider}/callback', [AuthController::class,'handleProviderCallback']);
