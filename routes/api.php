<?php



use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\admin\PrivilegeController;
use App\Http\Controllers\admin\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PartnerController;

use App\Http\Controllers\User\AuthController as UserAuthController;
// use App\Http\Controllers\User\ProductController as UserProductController;



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




Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/categories', [CategoryController::class, 'all'])->name('categories.all');
    Route::post('/categories', [CategoryController::class, 'add'])->name('categories.add');
    Route::get('/categories/{key}', [CategoryController::class, 'details'])->name('categories.details');
    Route::put('/categories/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::delete('/categories/{id}', [CategoryController::class, 'delete'])->name('categories.delete');


    Route::get('/settings', [SettingController::class, 'all'])->name('settings.all');
    Route::post('/settings', [SettingController::class, 'add'])->name('settings.add');
    Route::get('/settings/{key}', [SettingController::class, 'details'])->name('settings.details');
    Route::put('/settings/{id}', [SettingController::class, 'edit'])->name('settings.edit');
    Route::delete('/settings/{id}', [SettingController::class, 'delete'])->name('settings.delete');

    
    Route::get('/languages', [LanguageController::class, 'all'])->name('languages.all');
    Route::get('/languages/{id}', [LanguageController::class, 'details'])->name('languages.details');
    Route::post('/languages', [LanguageController::class, 'add'])->name('languages.add');
    Route::put('/languages/{id}', [LanguageController::class, 'update'])->name('languages.update');
    Route::delete('/languages/{id}', [LanguageController::class, 'delete'])->name('languages.delete');



    Route::get('/products', [AdminProductController::class, 'all'])->name('products.all');
    Route::get('/products/{id}', [AdminProductController::class, 'details'])->name('products.details');
    Route::post('/products', [AdminProductController::class, 'add'])->name('products.add');
    Route::put('/products/{id}', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::delete('/products/{id}', [AdminProductController::class, 'delete'])->name('products.delete');

    

    
    Route::get('/pages', [PageController::class, 'all'])->name('pages.all');
    Route::get('/pages/{id}', [PageController::class, 'details'])->name('pages.details');
    Route::post('/pages', [PageController::class, 'add'])->name('pages.add');
    Route::put('/pages/{id}', [PageController::class, 'edit'])->name('pages.edit');
    Route::delete('/pages/{id}', [PageController::class, 'delete'])->name('pages.delete');


    Route::get('/pages', [PageController::class, 'all'])->name('pages.all');
    Route::get('/pages/{id}', [PageController::class, 'details'])->name('pages.details');
    Route::post('/pages', [PageController::class, 'add'])->name('pages.add');
    Route::put('/pages/{id}', [PageController::class, 'edit'])->name('pages.edit');
    Route::delete('/pages/{id}', [PageController::class, 'delete'])->name('pages.delete');


    Route::get('/news', [NewsController::class, 'all'])->name('news.all');
    Route::post('/news', [NewsController::class, 'add'])->name('news.add');
    Route::get('/news/slug/{slug}', [NewsController::class, 'getBySlug'])->name('news.getBySlug');
    Route::get('/news/{id}', [NewsController::class, 'details'])->name('news.details');
    Route::put('/news/{id}', [NewsController::class, 'edit'])->name('news.edit');
    Route::delete('/news/{id}', [NewsController::class, 'delete'])->name('news.delete');


    
    Route::get('/roles', action: [PrivilegeController::class, 'all'])->name('roles.all');
    Route::get('/permissions', action: [PrivilegeController::class, 'permissions'])->name('roles.permissions');

    // Route::get('/partners/{partner}', [PartnerController::class, 'show'])->name('partners.show');
    // Route::post('/partners', [PartnerController::class, 'add'])->name('partners.add');
    // Route::put('/partners/{partner}', [PartnerController::class, 'edit'])->name('partners.edit');
    // Route::delete('/partners/{partner}', [PartnerController::class, 'delete'])->name('partners.delete');


  
})->middleware("auth:admins");





// Route::group(["prefix" => ""], function () {

//     Route::get('/products', [UserProductController::class, 'all'])->name('products.all');
//     Route::get('/products/{product}', [UserProductController::class, 'show'])->name('products.show');
//     Route::post('/products', [UserProductController::class, 'store'])->name('products.store');
//     Route::put('/products/{product}', [UserProductController::class, 'update'])->name('products.update');
//     Route::delete('/products/{product}', [UserProductController::class, 'destroy'])->name('products.destroy');


// })->middleware("auth:users");


