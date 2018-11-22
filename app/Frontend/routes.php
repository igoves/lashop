<?php
Route::prefix('uploads/products/')->group(function () {
    Route::get('big/{pic}', ['as' => 'image.big', 'uses' => 'ImageController@big']);
    Route::get('medium/{pic}', ['as' => 'image.medium', 'uses' => 'ImageController@medium']);
    Route::get('small/{pic}', ['as' => 'image.small', 'uses' => 'ImageController@small']);
});

Route::group(['middleware' => 'shop'], function () {
//    Route::auth();

    Route::get('/', ['as' => 'home', 'uses' => 'Frontend\HomeController@index']);

    Route::get('{id}-{slug}', ['as' => 'products.show', 'uses' => 'Frontend\Shop\ProductController@show'])->where('id', '[0-9]+');

    Route::get('cart', ['as' => 'cart.index', 'uses' => 'Frontend\Shop\CartController@index']);
    Route::post('cart', ['as' => 'cart.store', 'uses' => 'Frontend\Shop\CartController@store']);
    Route::delete('cart/{id}', ['as' => 'cart.destroy', 'uses' => 'Frontend\Shop\CartController@destroy']);

    Route::post('search', ['as' => 'search', 'uses' => 'Frontend\SearchController@index']);
    Route::get('search/{story}', ['as' => 'search.index', 'uses' => 'Frontend\SearchController@index']);

    Route::get('{slug}.html', ['as' => 'pages.index', 'uses' => 'Frontend\PageController@index']);

    Route::post('orders', ['as' => 'orders.store', 'uses' => 'Frontend\Shop\OrderController@store']);

    Route::get('{path}', ['as' => 'categories.show', 'uses' => 'Frontend\Shop\CategoryController@show'])->where('path', '[a-zA-Z0-9/_-]+');
});