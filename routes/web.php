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

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('{id}-{slug}', ['as' => 'products.show', 'uses' => 'ProductsController@show'])->where('id', '[0-9]+');

Route::get('cart', ['as' => 'cart.index', 'uses' => 'CartController@index']);
Route::post('cart', ['as' => 'cart.store', 'uses' => 'CartController@store']);
Route::delete('cart/{id}', ['as' => 'cart.destroy', 'uses' => 'CartController@destroy']);

Route::post('search', ['as' => 'search', 'uses' => 'SearchController@index']);
Route::get('search/{story}', ['as' => 'search.index', 'uses' => 'SearchController@index']);

Route::get('{slug}.html', ['as' => 'pages.index', 'uses' => 'PagesController@index']);

Route::get('{slug}', ['as' => 'categories.show', 'uses' => 'CategoriesController@show']);

Route::post('orders', ['as' => 'orders.store', 'uses' => 'OrdersController@store']);

/*Route::get('test', function() {
    dd(Session::get('cart'));
    Session::forget('cart');
    echo "<pre>";
    print_r(Session::get('cart'));
    echo "</pre>";
});*/
