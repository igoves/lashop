<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
//use Lavary\Menu\Menu;
//use Illuminate\Support\Facades\Config;
//use App\Category;
//use App\Product;
//use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($path)
    {

        $path = explode('/', $path);
        $categories = Category::where('slug', end($path))->firstOrFail();
        $shop_cat = \Request::get('shop_cat');

        function CatMenuInit ($shop_cat) {
            foreach ( $shop_cat as $key => $cats ) {
//                if ( $cats['status'] == 1 ) {
                    $all_info[$key]['id'] = $cats['id'];
                    $all_info[$key]['parent'] = $cats['parent_id'];
                    $all_info[$key]['name'] = $cats['title'];
                    $all_info[$key]['alt'] = $cats['slug'];
//                }
            }
            return $all_info;
        }

        function CatMenuUrl( $id, $all_info ) {
            if ( ! $id ) return;
            $parent_id = $all_info[$id]['parent'];
            $url = $all_info[$id]['alt'];
            while ( $parent_id ) {
                $url = $all_info[$parent_id]['alt'] . '/' . $url;
                $parent_id = $all_info[$parent_id]['parent'];
            }
            return $url;
        }

        function CatMenu( $current = 0, $all_info = array(), $depth = 0, $category_id = 0 ) {
            static $build;
            $depth++;
            if ( \count( $all_info ) > 0 ) {
                $children = array();
                foreach ( $all_info as $cats ) {
                    if ( $current == $cats['parent'] ) {
                        $children[] = $cats['id'];
                    }
                }
                $subcount = \count( $children );
                if ( $current !== 0 ) {
                    $ss = $category_id == $current ? 'active' : $ss = '';
                    $prefix = $depth == 3 ? ' - ' : '';
                    $build .= "<a class='list-group-item list-group-item-action ".$ss."' href='/".CatMenuUrl( $current, $all_info  ) ."'>".$prefix." " . stripslashes( $all_info[$current]['name'] ) . '</a>';
                }

                    for ( $i = 0; $i <= $subcount; $i++ ) {
                        if ( $i === $subcount ) {

                        } else {
                            CatMenu( $children[$i], $all_info, $depth, $category_id );
                        }
                    }

            }
            return $build;
        }

        $category_menu = '';
        if ( empty($cat_menu) ) {
            $all_info = CatMenuInit($shop_cat);
            $cat_menu = CatMenu( 0, $all_info, 0, $categories->id );
            unset($all_info);
        }
        if ( !empty($cat_menu) ) $category_menu = '<div class="list-group">'.$cat_menu. '</div>';


//        $categories = Category::with('children')->where('slug', 'men')->where('parent_id',0)->firstOrFail();
//        $categories = Category::all();
//        if (\count($categories->children) === 0 && \count($path) >= 2 ) {
//            $categories = Category::with('children')->where('slug', $path[\count($path)-2])->firstOrFail();
//        }
//        dd($categories->toArray());
//        $products = Product::with('categories')->where('cat_id', $categories->id)->paginate(config('products_count'));

        $categories_child = Category::with('children')->select('id')->where('parent_id',$categories->id)->get()->toArray();
//        dd($categories_child);

        $products = Product::whereIn('cat_id', $categories_child)->orWhere('cat_id', $categories->id)->paginate(config('products_count'));

        return view('frontend.shop.category', ['products' => $products, 'categories' => $categories, 'category_menu'=>$category_menu]);
    }
}