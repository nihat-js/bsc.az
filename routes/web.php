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
    

    return Admin::find(1)->permissions;
});


