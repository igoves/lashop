<?php

namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show($id, $slug)
    {
        $product = Product::with('categories')->where('id', $id)->where('slug', $slug)->where('status', 1)->firstOrFail();
        return view('frontend.' . config('template') . '.shop.products.showfull', compact('product'));
    }

}
