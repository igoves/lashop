<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {

        $categories = DB::table('categories')->where('slug', $slug)->first();
        $products = DB::table('products')->where('cat_id', $categories->id)->paginate(6);

        return view('categories.show', ['products' => $products, 'categories' => $categories]);
    }
}
