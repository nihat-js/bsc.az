<?php

use App\Http\Controllers\UserAuthController;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/test", function () {
    // Auth::loginUsingId(1,true);
    // Auth::logi
    // Auth::guard("admin")->loginUsingId(1,true);
    // Auth::guard("admin")->
    // return session()->all();

    // return Auth::guard("admin")->user();
    // return Auth::user();
    // $a = Admin::create([
    //     "name" => "admin",
    //     "email" => "admin@admin.com",
    //     "password" => bcrypt("adminadmin")
    // ]);
    // return $a;
});


