<?php

use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\User\ProductController as UserProductController;

use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\PartnerController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Route::get("/test",function(){
// auth("admin")->loginUsingId(1);
// echo "adsa";
// $user = auth("admin")->user()-givePermissions("aa");
// return $user;
// });


Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('logout', [UserAuthController::class, 'logout']);
Route::post('test', [UserAuthController::class, 'test'])->middleware('auth:users');



Route::post('admin/register', [AdminAuthController::class, 'register'])->name('admin.register');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::post('admin/test', [AdminAuthController::class, 'test'])->name('admin.test')->middleware('auth:admins');





Route::group(["prefix" => ""], function () {

    Route::get('/products', [UserProductController::class, 'all'])->name('products.all');
    Route::get('/products/{product}', [UserProductController::class, 'show'])->name('products.show');
    Route::post('/products', [UserProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [UserProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [UserProductController::class, 'destroy'])->name('products.destroy');


    Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/{partner}', [PartnerController::class, 'show'])->name('partners.show');
    Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');
    Route::put('/partners/{partner}', [PartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])->name('partners.destroy');

})->middleware("auth:users");



Route::prefix('admin')->name('admin.')->group(function () {


    Route::get('/settings', [SettingController::class, 'all'])->name('settings.all');
    Route::post('/settings', [SettingController::class, 'add'])->name('settings.add');
    Route::get('/settings/{key}', [SettingController::class, 'details'])->name('settings.details');
    Route::put('/settings/{id}', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings/{id}', [SettingController::class, 'delete'])->name('settings.delete');


    // Route::post('/categories', [SettingController::class, 'create'])->name('categories.create');
    // Route::get('/categories/{id}', [SettingController::class, 'details'])->name('categories.details');
    // Route::put('/categories/{id}', [SettingController::class, 'update'])->name('categories.update');
    // Route::delete('/categories/{id}', [SettingController::class, 'destroy'])->name('categories.delete');

    Route::get('/products', [AdminProductController::class, 'all'])->name('products.all');
    Route::get('/products/{id}', [AdminProductController::class, 'details'])->name('products.details');
    Route::post('/products', [AdminProductController::class, 'add'])->name('products.add');
    Route::put('/products/{id}', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::delete('/products/{id}', [AdminProductController::class, 'delete'])->name('products.delete');

    Route::get('/languages', [LanguageController::class, 'index'])->name('languages.index');
    Route::get('/languages/{id}', [LanguageController::class, 'show'])->name('languages.show');
    Route::post('/languages', [LanguageController::class, 'store'])->name('languages.store');
    Route::put('/languages/{id}', [LanguageController::class, 'update'])->name('languages.update');
    Route::delete('/languages/{id}', [LanguageController::class, 'destroy'])->name('languages.destroy');

  
});
