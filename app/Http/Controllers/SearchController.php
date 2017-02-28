<?php

namespace App\Http\Controllers;

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
                        ->paginate(6);

        return view('search.index', ['products' => $products, 'phrase' => $text]);
    }
}
