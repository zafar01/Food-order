<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;

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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/


Route::group(['middleware' => 'auth:sanctum'], function(){

Route::get('/food',[FoodController::class,'index']);
Route::post('/food',[FoodController::class,'store']);
Route::get('/food/{id}',[FoodController::class,'show']);
Route::post('/food/{id}',[FoodController::class,'update']);
Route::delete('/food/{id}',[FoodController::class,'destroy']);


Route::get('/category',[CategoryController::class,'index']);
Route::post('/category',[CategoryController::class,'store']);
Route::get('/category/{id}',[CategoryController::class,'show']);
Route::put('/category/{id}',[CategoryController::class,'update']);
Route::delete('/category/{id}',[CategoryController::class,'destroy']);

Route::get('customer',[AdminController::class,'customre']);

});



Route::post('/login', [AdminController::class,'index']);
Route::post('/register/admin', [RegisterController::class,'createAdmin']);
Route::post('/register/customer',[RegisterController::class,'createCustomer']);
 
 
 
 