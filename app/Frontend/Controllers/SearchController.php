<?php
namespace App\Http\Controllers\Frontend;

use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $text = $request->story;

        if ( $text === null || empty($text) ) {
            return redirect()->route('home');
        }
        $products = Product::where('title', 'like', '%' . $text . '%')
                            ->where('status', 1)
                            ->orWhere('fulldesc', 'like', '%' . $text . '%')
                            ->paginate(config('search_count'));

        $request->flash();

        return view('frontend.'.config('template').'.search', ['products' => $products, 'phrase' => $text]);
    }
}