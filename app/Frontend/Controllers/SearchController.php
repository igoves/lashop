<?php
namespace App\Http\Controllers\Frontend;

//use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $text = $request->story;
        if ( $text === null || empty($text) ) {
            return redirect()->route('home');
        }
        $products = DB::table('shop_products')
            ->where('title', 'like', '%' . $text . '%')
            ->orWhere('fulldesc', 'like', '%' . $text . '%')
            ->paginate(config('search_count'));

        $request->flash();

        return view('frontend.search', ['products' => $products, 'phrase' => $text]);
    }
}