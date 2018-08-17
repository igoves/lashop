<?php
namespace App\Http\Controllers\Frontend;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Shop\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::limit(3)->get();
        return view('frontend.home', ['products' => $products, 'breadcrumbs' => 'home']);
    }
}