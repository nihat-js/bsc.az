<?php



use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\admin\PrivilegeController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\CategoryFilterController;
use App\Http\Middleware\AdminPermissionMiddleware;
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
// })->middleware('auth:admin');


Route::get("/test", function () {
    // echo auth()->user();
    // echo ""
    // echo "adsa";
})->middleware("auth:admins");




Route::post('register', [UserAuthController::class, 'register'])->name('register');
Route::post('login', [UserAuthController::class, 'login'])->name('');
Route::post('logout', [UserAuthController::class, 'logout'])->name('')->middleware("auth:users");
// Route::post('test', [UserAuthController::class, 'test'])->middleware('auth:users');



Route::post('admin/register', [AdminAuthController::class, 'register'])->name('admin.register');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout')->middleware("auth:admins");
Route::post("admin/status", [AdminAuthController::class, 'status'])->name('admin.status')->middleware("auth:admins");
// Route::post('admin/test', [AdminAuthController::class, 'test'])->name('admin.test');




Route::prefix('admin')->name('admin.')->middleware(["auth:admins",AdminPermissionMiddleware::class])->group(function () {


    Route::get('/languages', [LanguageController::class, 'all'])->name('languages.all');
    Route::get('/languages/{id}', [LanguageController::class, 'one'])->name('languages.one');
    Route::post('/languages', [LanguageController::class, 'add'])->name('languages.add');
    Route::put('/languages/{id}', [LanguageController::class, 'edit'])->name('languages.edit');
    Route::delete('/languages/{id}', [LanguageController::class, 'delete'])->name('languages.delete');

    Route::get('/categories', [CategoryController::class, 'all'])->name('categories.all');
    Route::get('/categories/getChild/{id}', [CategoryController::class, 'getChild'])->name('categories.getChild');
    Route::post('/categories', [CategoryController::class, 'add'])->name('categories.add');
    Route::get('/categories/{key}', [CategoryController::class, 'one'])->name('categories.one');
    Route::put('/categories/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::delete('/categories/{id}', [CategoryController::class, 'delete'])->name('categories.delete');



    Route::get('/settings', [SettingController::class, 'all'])->name('settings.all');
    Route::post('/settings', [SettingController::class, 'add'])->name('settings.add');
    Route::get('/settings/{id}', [SettingController::class, 'one'])->name('settings.one')
        ->where('id', '[0-9]+');
    Route::get('/settings/{key}', [SettingController::class, 'oneByKey'])->name('settings.oneByKey')
        ->where('key', '[A-Za-z]+');
    Route::put('/settings/{id}', [SettingController::class, 'edit'])->name('settings.edit');
    Route::delete('/settings/{id}', [SettingController::class, 'delete'])->name('settings.delete');





    Route::get('/products', [AdminProductController::class, 'all'])->name('products.all');
    Route::get('/products/{id}', [AdminProductController::class, 'one'])->name('products.one');
    Route::post('/products', [AdminProductController::class, 'add'])->name('products.add');
    Route::put('/products/{id}', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::delete('/products/{id}', [AdminProductController::class, 'delete'])->name('products.delete');
    Route::post("/products/uplodImage", [AdminProductController::class, 'uploadImage'])->name('products.uploadImage');
    Route::post("products/arrangeImages", [AdminProductController::class, 'arrangeImages'])->name('products.arrangeImages');




    Route::get('/pages', [PageController::class, 'all'])->name('pages.all');
    Route::get('/pages/{id}', [PageController::class, 'one'])->name('pages.one');
    Route::post('/pages', [PageController::class, 'add'])->name('pages.add');
    Route::put('/pages/{id}', [PageController::class, 'edit'])->name('pages.edit');
    Route::delete('/pages/{id}', [PageController::class, 'delete'])->name('pages.delete');


    Route::get('/pages', [PageController::class, 'all'])->name('pages.all');
    Route::get('/pages/{id}', [PageController::class, 'one'])->name('pages.one');
    Route::post('/pages', [PageController::class, 'add'])->name('pages.add');
    Route::put('/pages/{id}', [PageController::class, 'edit'])->name('pages.edit');
    Route::delete('/pages/{id}', [PageController::class, 'delete'])->name('pages.delete');


    Route::get('/news', [NewsController::class, 'all'])->name('news.all');
    Route::post('/news', [NewsController::class, 'add'])->name('news.add');
    Route::get('/news/slug/{slug}', [NewsController::class, 'getBySlug'])->name('news.getBySlug');
    Route::get('/news/{id}', [NewsController::class, 'one'])->name('news.one');
    Route::put('/news/{id}', [NewsController::class, 'edit'])->name('news.edit');
    Route::delete('/news/{id}', [NewsController::class, 'delete'])->name('news.delete');



    Route::get('/roles', action: [PrivilegeController::class, 'all'])->name('privili.all');

    Route::get('/permissions', action: [PrivilegeController::class, 'permissions'])->name('privil.permissions');
    // Route::post("/permissions", [PrivilegeController::class, 'addPermission'])->name('privil.addPermission'); islenmir rolda avtomatik elave olunur

    



    Route::post("/permissions", [PrivilegeController::class, 'addPermission'])->name('privil.addPermission');

    // Route::get('/partners/{partner}', [PartnerController::class, 'show'])->name('partners.show');
    // Route::post('/partners', [PartnerController::class, 'add'])->name('partners.add');
    // Route::put('/partners/{partner}', [PartnerController::class, 'edit'])->name('partners.edit');
    // Route::delete('/partners/{partner}', [PartnerController::class, 'delete'])->name('partners.delete');

    // Route::get("/category-filter/category/{slug}", [CategoryFilterController::class, 'all'])->name('filters.add');
    // Route::post("/category-filter/", [CategoryFilterController::class, 'edit'])->name('filters.edit');


    Route::get("/admins", [AdminController::class, 'all'])->name('admins.all');
    Route::post("/admin", [AdminController::class, 'add'])->name('admins.add');
    Route::get("admins/{id}", [AdminAuthController::class, 'one'])->name('admins.one');

    




});





// Route::group(["prefix" => ""], function () {

//     Route::get('/products', [UserProductController::class, 'all'])->name('products.all');
//     Route::get('/products/{product}', [UserProductController::class, 'show'])->name('products.show');
//     Route::post('/products', [UserProductController::class, 'store'])->name('products.store');
//     Route::put('/products/{product}', [UserProductController::class, 'update'])->name('products.update');
//     Route::delete('/products/{product}', [UserProductController::class, 'destroy'])->name('products.destroy');


// })->middleware("auth:users");


