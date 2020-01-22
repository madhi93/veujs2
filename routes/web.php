<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix'=>'/' , 'namespace' => 'Auth'], function(){ 

    Route::get('/login', 'LoginController@login');
    Route::post('/login', 'LoginController@Authenticate');
    Route::get('/logout', 'LoginController@logout');
    Route::get('/forgot-password', 'LoginController@ForgotPasswordView');
    Route::post('/forgot-password' , 'LoginController@sendResetLinkEmail');
    Route::get('/reset-password' , 'LoginController@ResetView');
    Route::post('/reset-password' , 'LoginController@ResetPassword');
    Route::get('/create', 'LoginController@RegisterUser');

});

Route::get('/dashboard', 'HomeController@index');
Route::get('/fetch/cities', 'HomeController@fetchCities');
Route::get('/fetch/products', 'HomeController@fetchProducts');
Route::get('/fetch/delivery-hubs', 'HomeController@fetchHubs');
Route::get('/fetch/roles', 'HomeController@roles');

//Roles
Route::get('/admin/roles', 'HomeController@index');
Route::get('/admin/roles/create', 'HomeController@index');
Route::get('/admin/roles/{id}/edit', 'HomeController@index');
Route::get('/admin/roles/{id}/permissions', 'HomeController@index');

Route::group(['prefix'=>'/admin/role' , 'namespace' => 'Admin'], function(){ 
    Route::get('/list', 'RoleController@list');
    Route::post('/edit/{id}', 'RoleController@update');
    Route::post('/create', 'RoleController@store');
    Route::get('/fetch/{id}', 'RoleController@fetch');
    Route::get('/get-permissions', 'RoleController@fetchPermissions');
    Route::post('/{id}/update/permissions', 'RoleController@update_permissions');

});

//Permission
Route::get('/admin/permissions', 'HomeController@index');
Route::get('/admin/permissions/create', 'HomeController@index');
Route::get('/admin/permissions/{id}/edit', 'HomeController@index');

Route::group(['prefix'=>'/admin/permission' , 'namespace' => 'Admin'], function(){ 
    Route::get('/list', 'PermissionController@list');
    Route::post('/edit/{id}', 'PermissionController@update');
    Route::post('/create', 'PermissionController@store');
    Route::get('/fetch/{id}', 'PermissionController@fetch');

});

//Shop Categories
Route::get('/shop-categories', 'HomeController@index');
Route::get('/shop-categories/create', 'HomeController@index');
Route::get('/shop-categories/{id}/edit', 'HomeController@index');

Route::group(['prefix'=>'/shop-category'], function(){ 
    Route::get('/list', 'ShopCategoryController@list');
    Route::post('/edit/{id}', 'ShopCategoryController@update');
    Route::post('/create', 'ShopCategoryController@store');
    Route::get('/fetch/{id}', 'ShopCategoryController@fetch');
    Route::post('/images/upload', 'ShopCategoryController@uploads');
    Route::post('/feature-image/upload', 'ShopCategoryController@upload_feature_image');
    Route::delete('/remove-image/{id}', 'ShopCategoryController@destroy_media');

});

//Shops
Route::get('/shops', 'HomeController@index');
Route::get('/shops/create', 'HomeController@index');
Route::get('/shops/{id}/edit', 'HomeController@index');
Route::get('/shops/{id}/stocks', 'HomeController@index');

Route::group(['prefix'=>'/shop'], function(){ 
    Route::get('/list', 'ShopController@list');
    Route::post('/edit/{id}', 'ShopController@update');
    Route::post('/create', 'ShopController@store');
    Route::get('/fetch/{id}', 'ShopController@fetch');
    Route::get('/categories/fetch/{page?}', 'ShopController@fetch_categories');
    Route::post('/images/upload', 'ShopController@uploads');
    Route::post('/feature-image/upload', 'ShopController@upload_feature_image');
    Route::delete('/remove-image/{id}', 'ShopController@destroy_media');
});


//Product Categories
Route::get('/product-categories', 'HomeController@index');
Route::get('/product-categories/create', 'HomeController@index');
Route::get('/product-categories/{id}/edit', 'HomeController@index');

Route::group(['prefix'=>'/product-category'], function(){ 
    Route::get('/list', 'ProductCategoryController@list');
    Route::post('/edit/{id}', 'ProductCategoryController@update');
    Route::post('/create', 'ProductCategoryController@store');
    Route::get('/fetch/{id}', 'ProductCategoryController@fetch');
    Route::post('/images/upload', 'ProductCategoryController@uploads');
    Route::post('/feature-image/upload', 'ProductCategoryController@upload_feature_image');
    Route::delete('/remove-image/{id}', 'ProductCategoryController@destroy_media');

});

//Products
Route::get('/products', 'HomeController@index');
Route::get('/products/create', 'HomeController@index');
Route::get('/products/{id}/edit', 'HomeController@index');
Route::get('/products/{id}/stocks', 'HomeController@index');

//Shop Products
Route::get('/shops/{shop_id}/products', 'HomeController@index');
Route::get('/shops/{shop_id}/products/create', 'HomeController@index');
Route::get('/shops/{shop_id}/products/{id}/edit', 'HomeController@index');
Route::get('/shops/{shop_id}/products/{id}/stocks', 'HomeController@index');

Route::group(['prefix'=>'/product'], function(){ 
    Route::get('/list', 'ProductController@list');
    Route::post('/edit/{id}', 'ProductController@update');
    Route::post('/create', 'ProductController@store');
    Route::get('/fetch/{id}', 'ProductController@fetch');
    Route::get('/categories/fetch', 'ProductController@getCategories');
    Route::post('/images/upload', 'ProductController@uploads');
    Route::post('/feature-image/upload', 'ProductController@upload_feature_image');
    Route::delete('/remove-image/{id}', 'ProductController@destroy_media');

    Route::post('/stocks', 'StockController@histories');

});

//Advertisements
Route::get('/advertisements', 'HomeController@index');
Route::get('/advertisements/create', 'HomeController@index');
Route::get('/advertisements/{id}/edit', 'HomeController@index');
Route::get('/advertisements/{id}/stocks', 'HomeController@index');

Route::group(['prefix'=>'/advertisement'], function(){ 
    Route::get('/list', 'AdvertisementController@list');
    Route::post('/edit/{id}', 'AdvertisementController@update');
    Route::post('/create', 'AdvertisementController@store');
    Route::get('/fetch/{id}', 'AdvertisementController@fetch');
    Route::post('/feature-image/upload', 'AdvertisementController@upload_feature_image');
});


//Customers
Route::get('/customers', 'HomeController@index');
Route::get('/customers/create', 'HomeController@index');
Route::get('/customers/{id}/edit', 'HomeController@index');

Route::group(['prefix'=>'/customer'], function(){ 
    Route::get('/list', 'CustomerController@list');
    Route::post('/edit/{id}', 'CustomerController@update');
    Route::post('/create', 'CustomerController@store');
    Route::get('/fetch/{id}', 'CustomerController@fetch');

});

//Delivery Hubs
Route::get('/delivery-hubs', 'HomeController@index');
Route::get('/delivery-hubs/create', 'HomeController@index');
Route::get('/delivery-hubs/{id}/edit', 'HomeController@index');

Route::group(['prefix'=>'/delivery-hub'], function(){ 
    Route::get('/list', 'HubController@list');
    Route::post('/edit/{id}', 'HubController@update');
    Route::post('/create', 'HubController@store');
    Route::get('/fetch/{id}', 'HubController@fetch');

});

//Stock Purchases
Route::get('/purchases', 'HomeController@index');
Route::get('/purchases/create', 'HomeController@index');
Route::get('/purchases/{id}/edit', 'HomeController@index');

Route::group(['prefix'=>'/purchase'], function(){ 
    Route::get('/list', 'PurchaseController@list');
    Route::post('/edit/{id}', 'PurchaseController@update');
    Route::post('/create', 'PurchaseController@store');
    Route::get('/fetch/{id}', 'PurchaseController@fetch');

});

//Delivery Slots
Route::get('/delivery-slots', 'HomeController@index');
Route::get('/delivery-slots/create', 'HomeController@index');
Route::get('/delivery-slots/{id}/edit', 'HomeController@index');

Route::group(['prefix'=>'/delivery-slot'], function(){ 
    Route::get('/list', 'DeliverySlotController@list');
    Route::post('/edit/{id}', 'DeliverySlotController@update');
    Route::post('/create', 'DeliverySlotController@store');
    Route::get('/fetch/{id}', 'DeliverySlotController@fetch');

});

//Product Stocks
Route::get('/stocks', 'HomeController@index');

Route::group(['prefix'=>'/stock'], function(){ 
    Route::get('/list', 'StockController@list');
    Route::get('/fetch/{id}', 'StockController@fetch');

});

//Orders
Route::get('/orders', 'HomeController@index');
Route::get('/orders/{id}', 'HomeController@index');

Route::group(['prefix'=>'/order'], function(){ 
    Route::get('/list', 'OrderController@list');
    Route::post('/edit/{id}', 'OrderController@update');
    Route::post('/create', 'OrderController@store');
    Route::get('/fetch/{id}', 'OrderController@fetch');

});

Route::get('/deliveries', 'HomeController@index');
Route::get('/site-settings', 'HomeController@index');

//Delivery Areas
Route::get('/delivery-hubs/{hub_id}/delivery-areas', 'HomeController@index');

Route::group(['prefix'=>'/delivery-area'], function(){ 
    Route::get('/list', 'DeliveryAreaController@list');
    Route::post('/edit/{id}', 'DeliveryAreaController@update');
    Route::post('/create', 'DeliveryAreaController@store');
    Route::get('/fetch/{id}', 'DeliveryAreaController@fetch');

    Route::get('/fetch/{city_id}/tags', 'DeliveryAreaController@tags');

});

//Admin Users
Route::get('/admin/users', 'HomeController@index');
Route::get('/admin/users/create', 'HomeController@index');
Route::get('/admin/users/{id}/edit', 'HomeController@index');

Route::group(['prefix'=>'/admin/user'], function(){ 
    Route::get('/list', 'AdminController@list');
    Route::post('/edit/{id}', 'AdminController@update');
    Route::post('/create', 'AdminController@store');
    Route::get('/fetch/{id}', 'AdminController@fetch');

});