<?php



use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategorySpecsController;
use App\Http\Controllers\Admin\ColorCatalogController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DictionaryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PrivilegeController;
use App\Http\Controllers\Admin\ProductColorOptionController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\CategorySpecOptionController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\User\BasketController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Middleware\AdminPermissionMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PartnerController;

use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\ProductController as UserProductController;





Route::get("/developer-test", function () {

    $user = auth()->user();
    // $user->assignRole("Super Admin");
    $user->syncRoles(["Super Admin"]);
    return $user->roles->first()->name;

})->middleware("auth:admins");




$controller = UserAuthController::class;
Route::post('register', [$controller, 'register'])->name('register');
Route::post('login', [$controller, 'login'])->name('');
Route::post('logout', [$controller, 'logout'])->name('')->middleware("auth:users");
Route::post("status", [$controller, 'status'])->name('status')->middleware("auth:users");
// Route::post('test', [UserAuthController::class, 'test'])->middleware('auth:users');



Route::post('admin/register', [AdminAuthController::class, 'register'])->name('admin.register');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout')->middleware("auth:admins");
Route::post("admin/status", [AdminAuthController::class, 'status'])->name('admin.status')->middleware("auth:admins");
// Route::post('admin/test', [AdminAuthController::class, 'test'])->name('admin.test');



Route::group(["middleware" => "auth:users"], function () {


    $controller = BasketController::class;
    Route::get("/basket", [$controller, 'all'])->name('basket.all');
    Route::post("/basket", [$controller, 'add'])->name('basket.add');
    Route::put("/basket", [$controller, 'edit'])->name('basket.edit');
    Route::get("/basket/total", [$controller, 'total'])->name('basket.total');
    Route::delete("/basket", [$controller, 'clear'])->name('basket.clear');
    Route::delete("/basket/{productId}", [$controller, 'remove'])->name('basket.remove');


    $controller = WishlistController::class;
    Route::get("/wishlist",[$controller, 'all'])->name('wishlist.all');
    Route::post("/wishlist", [$controller, 'add'])->name('wishlist.add');
    Route::delete("/wishlist", [$controller, 'clear'])->name('wishlist.clear');
    Route::delete("/wishlist/{productId}", [$controller, 'remove'])->name('wishlist.remove');


    // Route::post("/basket/remove", [$controller, 'remove'])->name('basket.remove');
    // Route::post("/basket/checkout", [$controller, 'checkout'])->name('basket.checkout');
    // Route::post("/basket/order", [$controller, 'order'])->name('basket.order');
    // Route::post("/basket/order/{order}", [$controller, 'getOrder'])->name('basket.getOrder');
    // Route::post("/basket/order/{order}/cancel", [$controller, 'cancelOrder'])->name('basket.cancelOrder');
    // Route::post("/basket/order/{order}/return", [$controller, 'returnOrder'])->name('basket.returnOrder');


    Route::post("/add-to-cart", [UserProductController::class, 'addToCart'])->name('addToCart');
    Route::post("/remove-from-cart", [UserProductController::class, 'removeFromCart'])->name('removeFromCart');
    Route::post("/get-cart", [UserProductController::class, 'getCart'])->name('getCart');
    Route::post("/get-cart-count", [UserProductController::class, 'getCartCount'])->name('getCartCount');
    Route::post("/get-cart-total", [UserProductController::class, 'getCartTotal'])->name('getCartTotal');

    Route::post("add-to-wishlist", [UserProductController::class, 'addToWishlist'])->name('addToWishlist');
    Route::post("remove-from-wishlist", [UserProductController::class, 'removeFromWishlist'])->name('removeFromWishlist');
    Route::post("get-wishlist", [UserProductController::class, 'getWishlist'])->name('getWishlist');
    Route::post("get-wishlist-count", [UserProductController::class, 'getWishlistCount'])->name('getWishlistCount');

    Route::post("add-to-compare", [UserProductController::class, 'addToCompare'])->name('addToCompare');
    Route::post("remove-from-compare", [UserProductController::class, 'removeFromCompare'])->name('removeFromCompare');
    Route::post("get-compare", [UserProductController::class, 'getCompare'])->name('getCompare');

    Route::post("order", [UserProductController::class, 'order'])->name('order');
    Route::post("get-orders", [UserProductController::class, 'getOrders'])->name('getOrders');
    Route::post("get-order", [UserProductController::class, 'getOrder'])->name('getOrder');
    Route::post("cancel-order", [UserProductController::class, 'cancelOrder'])->name('cancelOrder');
    Route::post("return-order", [UserProductController::class, 'returnOrder'])->name('returnOrder');
    Route::post("get-order-status", [UserProductController::class, 'getOrderStatus'])->name('getOrderStatus');

    Route::post("get-products", [UserProductController::class, 'getProducts'])->name('getProducts');
    Route::post("get-product", [UserProductController::class, 'getProduct'])->name('getProduct');
    Route::post("get-product-by-slug", [UserProductController::class, 'getProductBySlug'])->name('getProductBySlug');
    Route::post("get-product-by-category", [UserProductController::class, 'getProductByCategory'])->name('getProductByCategory');
    Route::post("get-product-by-brand", [UserProductController::class, 'getProductByBrand'])->name('getProductByBrand');
    Route::post("get-product-by-country", [UserProductController::class, 'getProductByCountry'])->name('getProductByCountry');
    Route::post("get-product-by-partner", [UserProductController::class, 'getProductByPartner'])->name('getProductByPartner');
    Route::post("get-product-by-news", [UserProductController::class, 'getProductByNews'])->name('getProductByNews');
    Route::post("get-product-by-page", [UserProductController::class, 'getProductByPage'])->name('getProductByPage');


    Route::post("get-categories", [UserProductController::class, 'getCategories'])->name('getCategories');
    Route::post("get-category", [UserProductController::class, 'getCategory'])->name('getCategory');
    Route::post("get-category-by-parent", [UserProductController::class, 'getCategoryByParent'])->name('getCategoryByParent');

    Route::post("get-news", [UserProductController::class, 'getNews'])->name('getNews');
    Route::post("get-news-by-slug", [UserProductController::class, 'getNewsBySlug'])->name('getNewsBySlug');
    Route::post("get-news-by-category", [UserProductController::class, 'getNewsByCategory'])->name('getNewsByCategory');
    Route::post("get-news-by-page", [UserProductController::class, 'getNewsByPage'])->name('getNewsByPage');



});



Route::prefix('admin')->name('admin.')->middleware(["auth:admins", AdminPermissionMiddleware::class])->group(function () {


    Route::get("/category-specs", [CategorySpecsController::class, 'all'])->name('categorySpecs.all');
    Route::get("/category-specs/{categoryId}", [CategorySpecsController::class, 'one'])->name('categorySpecs.one')
        ->where('categoryId', '[0-9]+');
    Route::get("/category-specs/category/{categoryId}", [CategorySpecsController::class, 'getByCategory'])->name('categorySpecs.getByCategory');
    Route::post("/category-specs", [CategorySpecsController::class, 'add'])->name('categorySpecs.add');
    Route::put("/category-specs/{id}", [CategorySpecsController::class, 'edit'])->name('categorySpecs.edit');
    Route::delete("/category-specs/{id}", [CategorySpecsController::class, 'delete'])->name('categorySpecs.delete');


    Route::get('/languages', [LanguageController::class, 'all'])->name('languages.all');
    Route::get('/languages/{id}', [LanguageController::class, 'one'])->name('languages.one');
    Route::post('/languages', [LanguageController::class, 'add'])->name('languages.add');
    Route::put('/languages/{id}', [LanguageController::class, 'edit'])->name('languages.edit');
    Route::delete('/languages/{id}', [LanguageController::class, 'delete'])->name('languages.delete');

    Route::get('/categories', [CategoryController::class, 'all'])->name('categories.all');
    Route::get('/categories/getChild/{id}', [CategoryController::class, 'getChild'])->name('categories.getChild');
    Route::post('/categories', [CategoryController::class, 'add'])->name('categories.add');
    Route::get('/categories/{id}', [CategoryController::class, 'one'])->name('categories.one')
        ->where('id', '[0-9]+');
    Route::put('/categories/{id}', [CategoryController::class, 'edit'])->name('categories.edit')
        ->where('id', '[0-9]+');
    Route::delete('/categories/{id}', [CategoryController::class, 'delete'])->name('categories.delete')
        ->where('id', '[0-9]+');
    Route::get("/categories/leafCategories", [CategoryController::class, 'leafCategories'])->name('categories.leafCategories');



    Route::get('/settings', [SettingController::class, 'all'])->name('settings.all');
    Route::post('/settings', [SettingController::class, 'add'])->name('settings.add');
    Route::get('/settings/{id}', [SettingController::class, 'one'])->name('settings.one')
        ->where('id', '[0-9]+');
    Route::get('/settings/{key}', [SettingController::class, 'oneByKey'])->name('settings.oneByKey')
        ->where('key', '[A-Za-z]+');
    Route::put('/settings/{id}', [SettingController::class, 'edit'])->name('settings.edit')
        ->where("id", "[0-9]+");
    Route::put("/settings/bulkUpdate", [SettingController::class, 'bulkUpdate'])->name('settings.bulkUpdate');
    Route::delete('/settings/{id}', [SettingController::class, 'delete'])->name('settings.delete');





    Route::get('/products', [AdminProductController::class, 'all'])->name('products.all');
    Route::get('/products/{id}', [AdminProductController::class, 'one'])->name('products.one')
        ->where('id', '[0-9]+');
    Route::post('/products', [AdminProductController::class, 'add'])->name('products.add');
    Route::put('/products/{id}', [AdminProductController::class, 'edit'])->name('products.edit')
        ->where('id', '[0-9]+');
    Route::delete('/products/{id}', [AdminProductController::class, 'delete'])->name('products.delete');
    Route::post("/products/uplodImage", [AdminProductController::class, 'uploadImage'])->name('products.uploadImage');
    Route::post("products/arrangeImages", [AdminProductController::class, 'arrangeImages'])->name('products.arrangeImages');

    Route::delete("/products/{id}/deleteCoverImage", [AdminProductController::class, 'deleteCoverImage'])
        ->name('products.deleteCoverImage');

    Route::delete("/products/{id}/deleteImage/{imageId}", [AdminProductController::class, 'deleteImage'])
        ->name('products.deleteImage');




    Route::get('/pages', [PageController::class, 'all'])->name('pages.all');
    Route::get('/pages/{id}', [PageController::class, 'one'])->name('pages.one');
    Route::post('/pages', [PageController::class, 'add'])->name('pages.add');
    Route::put('/pages/{id}', [PageController::class, 'edit'])->name('pages.edit');
    Route::delete('/pages/{id}', [PageController::class, 'delete'])->name('pages.delete');





    Route::get('/news', [NewsController::class, 'all'])->name('news.all');
    Route::post('/news', [NewsController::class, 'add'])->name('news.add');
    Route::get('/news/{id}', [NewsController::class, 'one'])->name('news.one')
        ->where('id', '[0-9]+');
    Route::put('/news/{id}', [NewsController::class, 'edit'])->name('news.edit');
    Route::delete('/news/{id}', [NewsController::class, 'delete'])->name('news.delete');
    // Route::get('/news/slug/{slug}', [NewsController::class, 'getBySlug'])->name('news.getBySlug');



    Route::get('/roles', action: [PrivilegeController::class, 'roles'])->name('priviliege.roles');
    Route::get('/permissions', action: [PrivilegeController::class, 'permissions'])->name('privilege.permissions');
    Route::post("/permissions", [PrivilegeController::class, 'addPermission'])->name('privilege.addPermission');


    Route::get("/admins", [AdminController::class, 'all'])->name('admins.all');
    Route::get("/admins/{id}", [AdminController::class, 'one'])->name('admins.one');
    Route::post("/admins", [AdminController::class, 'add'])->name('admins.add');
    Route::put("/admins/{id}", [AdminController::class, 'edit'])->name('admins.edit');
    Route::delete("/admins/{id}", [AdminController::class, 'delete'])->name('admins.delete');
    Route::delete("/admins", [AdminController::class, 'bulkDelete'])->name('admins.bulkDelete');




    Route::get("/brands", [BrandController::class, 'all'])->name('brands.all');
    Route::get("/brands/{id}", [BrandController::class, 'one'])->name('brands.one');
    Route::post("/brands", [BrandController::class, 'add'])->name('brands.add');
    Route::put("/brands/{id}", [BrandController::class, 'edit'])->name('brands.edit');
    Route::delete("/brands/{id}", [BrandController::class, 'delete'])->name('brands.delete');

    Route::get("/countries", [CountryController::class, 'all'])->name('countries.all');
    Route::get("/countries/{id}", [CountryController::class, 'one'])->name('countries.one');
    Route::post("/countries", [CountryController::class, 'add'])->name('countries.add');
    Route::put("/countries/{id}", [CountryController::class, 'edit'])->name('countries.edit');
    Route::delete("/countries/{id}", [CountryController::class, 'delete'])->name('countries.delete');

    $controller = PartnerController::class;
    Route::get("/partners", [$controller, 'all'])->name('partners.all');
    Route::get("/partners/{id}", [$controller, 'one'])->name('partners.one');
    Route::post("/partners", [$controller, 'add'])->name('partners.add');
    Route::put("/partners/{id}", [$controller, 'edit'])->name('partners.edit');
    Route::delete("/partners/{id}", [$controller, 'delete'])->name('partners.delete');


    $controller = DictionaryController::class;
    Route::get("/dictionary/{id}", [$controller, 'one'])->name('dictionary.one')
        ->where('id', '[0-9]+');
    Route::get("/dictionary", [$controller, 'all'])->name('dictionary.all');
    Route::post("/dictionary", [$controller, 'add'])->name('dictionary.add');
    Route::put("/dictionary/{id}", [$controller, 'edit'])->name('dictionary.edit');
    Route::delete("/dictionary/{id}", [$controller, 'delete'])->name('dictionary.delete');
    Route::get("/dictionary/language/{lang_code}", [$controller, 'byLanguage'])
        ->name('dictionary.allLanguage');


    $controller = CategorySpecOptionController::class;
    Route::post("/category-spec-options", [$controller, 'add'])->name('categorySpecOptions.add');
    Route::post("/category-spec-options/bulk", [$controller, 'bulkAdd'])->name('categorySpecOptions.bulkAdd');
    Route::get("/category-spec-options", [$controller, 'all'])->name('categorySpecOptions.all');
    Route::delete("/category-spec-options/{id}", [$controller, 'delete'])->name('categorySpecOptions.delete');

    // Route::get("/category-spec-options/category/{categoryId}", [CategorySpecOptionController::class, 'categoryOptions'])->name('categorySpecs.categoryOptions');    


    $controller = ColorCatalogController::class;
    Route::get("/color-catalog", [$controller, 'all'])->name('productColorOptions.all');
    Route::get("/color-catalog/{id}", [$controller, 'one'])->name('productColorOptions.one');
    Route::post("/color-catalog", [$controller, 'add'])->name('productColorOptions.add');
    Route::put("/color-catalog/{id}", [$controller, 'edit'])->name('productColorOptions.edit');
    Route::delete("/color-catalog/{id}", [$controller, 'delete'])->name('productColorOptions.delete');




    Route::get("/filter/category/id/{categoryId}", [FilterController::class, 'all'])->name('filter.all');


});





// Route::group(["prefix" => ""], function () {

//     Route::get('/products', [UserProductController::class, 'all'])->name('products.all');
//     Route::get('/products/{product}', [UserProductController::class, 'show'])->name('products.show');
//     Route::post('/products', [UserProductController::class, 'store'])->name('products.store');
//     Route::put('/products/{product}', [UserProductController::class, 'update'])->name('products.update');
//     Route::delete('/products/{product}', [UserProductController::class, 'destroy'])->name('products.destroy');


// })->middleware("auth:users");


