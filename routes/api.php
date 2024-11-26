<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserAuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('logout', [UserAuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::post("/balance", function () {
    return "User balance is";
});

Route::get("/products", [ProductController::class, "all"]);

Route::group(["middleware" => "auth:sanctum"], function () {

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');


    Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/{partner}', [PartnerController::class, 'show'])->name('partners.show');
    Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');
    Route::put('/partners/{partner}', [PartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])->name('partners.destroy');

})->middleware("auth:sanctum");


Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('register', [AdminAuthController::class, 'register'])->name('register');
    Route::get('login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});


Route::prefix('admin')->name('admin.')->group(function () {


    // settings
    Route::get('/settings', [SettingController::class, 'all'])->name('settings.all');
    Route::post('/settings', [SettingController::class, 'create'])->name('settings.create');
    Route::get('/settings/{id}', [SettingController::class, 'details'])->name('settings.details');
    Route::put('/settings/{id}', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings/{id}', [SettingController::class, 'destroy'])->name('settings.delete');

    Route::get('/categories', [Category::class, 'all'])->name('categories.all');
    Route::post('/categories', [SettingController::class, 'create'])->name('categories.create');
    Route::get('/categories/{id}', [SettingController::class, 'details'])->name('categories.details');
    Route::put('/categories/{id}', [SettingController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [SettingController::class, 'destroy'])->name('categories.delete');




    

    Route::get("test", function () {
        return "Admin test";
    });
    // Route::get('dashboard', [::class, 'index'])->name('dashboard');
});
