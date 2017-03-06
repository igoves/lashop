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

Route::get('admin', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'shop'], function () {
    Route::auth();

    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

    Route::get('{id}-{slug}', ['as' => 'products.show', 'uses' => 'ProductsController@show'])->where('id', '[0-9]+');

    Route::get('cart', ['as' => 'cart.index', 'uses' => 'CartController@index']);
    Route::post('cart', ['as' => 'cart.store', 'uses' => 'CartController@store']);
    Route::delete('cart/{id}', ['as' => 'cart.destroy', 'uses' => 'CartController@destroy']);

    Route::post('search', ['as' => 'search', 'uses' => 'SearchController@index']);
    Route::get('search/{story}', ['as' => 'search.index', 'uses' => 'SearchController@index']);

    Route::get('{slug}.html', ['as' => 'pages.index', 'uses' => 'PagesController@index']);

    Route::get('{slug}', ['as' => 'categories.show', 'uses' => 'CategoriesController@show'])->where(['slug' => '[\w\d\-\_]+']);

    Route::post('orders', ['as' => 'orders.store', 'uses' => 'OrdersController@store']);
});

