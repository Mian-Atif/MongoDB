<?php
use App\Http\Controllers\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\UserAuthentication;

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

Route::post("addpost",[UserPost::class,"addPost"])->middleware('UserAuthentication');
Route::post("addfriend",[UserPost::class,"addFriend"])->middleware('UserAuthentication');
Route::post("addcomment",[UserPost::class,"addComment"])->middleware('UserAuthentication');
Route::post("updatecomment",[UserPost::class,"updateComment"])->middleware('UserAuthentication');
Route::post("deletecomment",[UserPost::class,"deleteComment"])->middleware('UserAuthentication');
