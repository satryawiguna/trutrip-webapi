<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/register/member', 'Api\V1\Auth\RegisterController@actionRegister')->name('api.register');
Route::post('/password/email', 'Api\V1\Auth\ForgotPasswordController@actionSendResetLinkEmail')->name('api.send_reset_link_email');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'Api\V1\Auth\AuthController@actionLogin')->name('api.login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/me', 'Api\V1\Auth\AuthController@actionMe')->name('api.me');
        Route::post('/logout', 'Api\V1\Auth\AuthController@actionLogout')->name('api.logout');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'profile'], function () {
    Route::get('/me', 'Api\V1\MembershipController@actionProfile')->name('api.profile');
    Route::put('/me/update', 'Api\V1\MembershipController@actionProfileUpdate')->name('api.profile_update');
    Route::post('/photo/update', 'Api\V1\MembershipController@actionPhotoUpdate')->name('api.photo_update');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/users', 'Api\V1\UserController@actionUsers')->name('api.users');
    Route::get('/user/{id}', 'Api\V1\UserController@actionUser')->name('api.user');
    Route::post('/user/store', 'Api\V1\UserController@actionUserStore')->name('api.user_store');
    Route::group(['prefix' => 'user'], function () {
        Route::put('/{id}/update', 'Api\V1\UserController@actionUserUpdate')->name('api.user_update');
        Route::delete('/{id}/delete', 'Api\V1\UserController@actionUserDelete')->name('api.user_delete');
    });

    Route::get('/categories', 'Api\V1\CategoryController@actionCategories')->name('api.categories');
    Route::post('/categoriesListSearch', 'Api\V1\CategoryController@actionCategoriesListSearch')->name('api.categories_list_search');
    Route::post('/categoriesPageSearch', 'Api\V1\CategoryController@actionCategoriesPageSearch')->name('api.categories_page_search');
    Route::get('/category/{id}', 'Api\V1\CategoryController@actionCategory')->name('api.category');
    Route::post('/category/store', 'Api\V1\CategoryController@actionCategoryStore')->name('api.category_store');
    Route::prefix('category')->group(function () {
        Route::put('/{id}/update', 'Api\V1\CategoryController@actionCategoryUpdate')->name('api.category_update');
        Route::delete('/{id}/delete', 'Api\V1\CategoryController@actionCategoryDelete')->name('api.category_delete');
    });

    Route::get('/products', 'Api\V1\ProductController@actionProducts')->name('api.products');
    Route::post('/productsListSearch', 'Api\V1\ProductController@actionProductsListSearch')->name('api.products_list_search');
    Route::post('/productsPageSearch', 'Api\V1\ProductController@actionProductsPageSearch')->name('api.products_page_search');
    Route::get('/product/{id}', 'Api\V1\ProductController@actionProduct')->name('api.product');
    Route::post('/product/store', 'Api\V1\ProductController@actionProductStore')->name('api.product_store');
    Route::prefix('product')->group(function () {
        Route::put('/{id}/update', 'Api\V1\ProductController@actionProductUpdate')->name('api.product_update');
        Route::delete('/{id}/delete', 'Api\V1\ProductController@actionProductDelete')->name('api.product_delete');
    });

});


