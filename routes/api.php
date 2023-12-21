<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->group( function () {
Route::get('logout',[AuthController::class,'logout']);
Route::get('/user-data',[AuthController::class,'getUserData']);
Route::get('/profile-setting',[AuthController::class,'profileSetting']);
Route::post('/save-profile-setting',[AuthController::class,'saveProfileSetting']);
Route::post('/do-feature',[AuthController::class,'doFeature']);
Route::post('/view-listing-detail',[AuthController::class,'getListingDetails']);

});
