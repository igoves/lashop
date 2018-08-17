<?php

Route::group(['middleware' => 'shop'], function () {
//    Route::auth();

    Route::get('/', ['as' => 'home', 'uses' => 'Frontend\HomeController@index']);

    Route::get('{id}-{slug}', ['as' => 'products.show', 'uses' => 'Frontend\ProductController@show'])->where('id', '[0-9]+');

    Route::get('cart', ['as' => 'cart.index', 'uses' => 'Frontend\CartController@index']);
    Route::post('cart', ['as' => 'cart.store', 'uses' => 'Frontend\CartController@store']);
    Route::delete('cart/{id}', ['as' => 'cart.destroy', 'uses' => 'Frontend\CartController@destroy']);

    Route::post('search', ['as' => 'search', 'uses' => 'Frontend\SearchController@index']);
    Route::get('search/{story}', ['as' => 'search.index', 'uses' => 'Frontend\SearchController@index']);

    Route::get('{slug}.html', ['as' => 'pages.index', 'uses' => 'Frontend\PageController@index']);

    Route::post('orders', ['as' => 'orders.store', 'uses' => 'Frontend\OrderController@store']);

    Route::get('{path}', ['as' => 'categories.show', 'uses' => 'Frontend\CategoryController@show'])->where('path', '[a-zA-Z0-9/_-]+');
});