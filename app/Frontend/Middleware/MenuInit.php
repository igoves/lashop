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
        $request->attributes->add(compact('shop_cat'));

        return $next($request);
    }
}