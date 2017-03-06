<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {

        $text = $request->get('story');

        if ( !isset($text) || empty($text) ) {
            return redirect()->route('home');
        }

        $products = DB::table('products')
                        ->where('name', 'like', '%' . $text . '%')
                        ->orWhere('desc', 'like', '%' . $text . '%')
                        ->paginate(Config::get('settings.search_cnt'));

        return view('search.index', ['products' => $products, 'phrase' => $text]);
    }
}
