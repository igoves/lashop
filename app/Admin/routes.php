<?php

Route::get('', ['as' => 'admin.dashboard', function () {

    $stats['categories'] = DB::table('categories')->count();
    $stats['products'] = DB::table('products')->count();
    $stats['pages'] = DB::table('pages')->count();
    $stats['orders'] = DB::table('orders')->count();
    $stats['revenue'] = DB::table('orders')->sum('total');;

	$content = '
        <h4>Shop Stats</h4>
        Number of Categories: '.$stats['categories'].' <br/>
        Number of Products: '.$stats['products'].' <br/>
        Number of Pages: '.$stats['pages'].' <br/>
        Number of Orders: '.$stats['orders'].' <br/>
        Revenue generated: '.$stats['revenue'].' $ <br/>
	';
	return AdminSection::view($content, 'Dashboard');
}]);


Route::post('products', ['as' => 'admin.products', 'uses' => '\App\Admin\Sections\Products@onDisplay']);

Route::post('categories', ['as' => 'admin.categories', 'uses' => '\App\Admin\Sections\Categories@onDisplay']);

Route::post('orders', ['as' => 'admin.orders', 'uses' => '\App\Admin\Sections\Orders@onDisplay']);

Route::post('pages', ['as' => 'admin.pages', 'uses' => '\App\Admin\Sections\Pages@onDisplay']);

Route::get('settings', ['as' => 'admin.settings', function () {

    $setting = \Illuminate\Support\Facades\Config::get('settings');
    $settings = '';
    foreach ( $setting as $key => $value ) {
        $settings .= $key.' - <pre><code>'.$value.'</code></pre>';
    }
    return AdminSection::view($settings, 'Settings');
}]);