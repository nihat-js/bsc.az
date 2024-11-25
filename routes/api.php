<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('logout', [UserAuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::post("/balance",function(){
    return "User balance is";
});

Route::get("/products",[ProductController::class,"all"]);

Route::group(["middleware" => "prefix"],{
    
})->middleware("auth:sanctum");