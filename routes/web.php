<?php

use App\Http\Controllers\UserAuthController;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

Route::get('/', function () {
    return "world is here";
    // return view('welcome');
});

Route::get("/error",function(){
    return response()->json(['message' => 'Unauthorized'], 401);
})->name("login");

