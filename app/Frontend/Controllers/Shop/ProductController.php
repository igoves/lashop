<?php
namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;

class ProductController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, $slug)
    {
        $product = Product::with('categories')->where('id', $id)->where('slug', $slug)->firstOrFail();
        return view('frontend.shop.products.showfull', compact('product'));
    }

}