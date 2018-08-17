<?php
namespace App\Frontend\Middleware;
use Closure;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Route;
//use Illuminate\Support\Facades\Request;
//use App\Product;
class MenuInit
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
//        Menu::make('menu', function($menu) use ($request) {
//            if ( $request->route()->getName() === 'products.show' ) {
//                $row = DB::table('products')->where('id', $request->route()->parameters()['id'])->first();
//            }
//            $category = DB::table('categories')->get();
//            foreach( $category as $key => $value ) {
//                if ( isset($row->cat_id) && $row->cat_id == $value->id ) {
//                    $menu->add($value->title, $value->slug)->active();
//                } else {
//                    $menu->add($value->title, $value->slug);
//                }
//            }
//            $menu->divide();
//            $pages = DB::table('pages')->get();
//            foreach( $pages as $key => $value ) {
//                $menu->add($value->title, $value->slug.'.html');
//            }
//        });

        \Menu::make('top_menu', function ($menu) {
            $category = DB::table('shop_categories')->where('parent_id', 0)->get();
            foreach( $category as $key => $value ) {
                $menu->add($value->title, ['url' => $value->slug, 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
            }
            $menu->divide();
            $pages = DB::table('pages')->get();
            foreach( $pages as $key => $value ) {
                $menu->add($value->title, ['url' => $value->slug.'.html', 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
            }
        });

        /* ============ категории магазина =============== */
        $shop_cat = array ();
        $shop_cat_sql  = DB::table('shop_categories')->get();
        foreach ( $shop_cat_sql as $key => $row ) {
            $row = json_decode(json_encode($row), true);
            $shop_cat[$row['id']] = array ();
            foreach ( $row as $key => $value ) {
                $shop_cat[$row['id']][$key] = stripslashes( $value );
            }
        }
//        foreach ( $shop_cat as $key ) {
//            $cat[$key['id']]['title'] = $key['title'];
//            $cat[$key['id']]['desc_ru'] = $key['desc_ru'];
//            $cat[$key['id']]['parent'] = $key['parent'];
//            $cat[$key['id']]['desc'] = $key['desc_ru'];
//            $cat[$key['id']]['photo'] = $key['photo'];
//            $cat_alt[$key['id']] = $key['slug'];
//        }
//        dd($shop_cat);
        $request->attributes->add(compact('shop_cat'));

        return $next($request);
    }
}