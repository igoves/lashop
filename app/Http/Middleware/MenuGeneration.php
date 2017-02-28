<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Route;
//use Illuminate\Support\Facades\Request;
//use App\Product;

class MenuGeneration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       \Menu::make('menu', function($menu) use ($request) {
            if ( $request->route()->getName() === 'products.show' ) {
               $row = DB::table('products')->where('id', $request->route()->parameters()['id'])->first();
            }
            $category = DB::table('categories')->get();
            foreach( $category as $key => $value ) {
                if ( isset($row->cat_id) && $row->cat_id == $value->id ) {
                    $menu->add($value->title, $value->slug)->active();
                } else {
                    $menu->add($value->title, $value->slug);
                }
            }
           $menu->divide();
           $pages = DB::table('pages')->get();
           foreach( $pages as $key => $value ) {
               $menu->add($value->title, $value->slug.'.html');
           }
       });

        return $next($request);
    }
}
