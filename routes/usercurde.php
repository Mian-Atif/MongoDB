<?php

use App\Http\Controllers\UserCrude;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::post("updateuser",[UserCrude::class,"updateUser"])->middleware('UserAuthentication');
Route::post("searchuser",[UserCrude::class,"searchUser"])->middleware('UserAuthentication');
Route::post("uploadfile",[UserCrude::class,"upLoadFile"])->middleware('UserAuthentication');
Route::post("forgetpassword",[UserCrude::class,"forGetPassword"]);






