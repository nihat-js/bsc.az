<?php

use App\Http\Controllers\PartnerController;
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

Route::post("/balance", function () {
    return "User balance is";
});

Route::get("/products", [ProductController::class, "all"]);

Route::group(["middleware" => "prefix"], function () {

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