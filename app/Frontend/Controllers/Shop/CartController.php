<?php

namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use LukePOLO\LaraCart\Facades\LaraCart;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart_data = [];
        foreach ($items = LaraCart::getItems() as $item) {
            $cart_data[$item->id]['id'] = $item->id;
            $cart_data[$item->id]['name'] = $item->name;
            $cart_data[$item->id]['image'] = $item->photo;
            $cart_data[$item->id]['slug'] = $item->slug;
            $cart_data[$item->id]['cost'] = $item->price;
            $cart_data[$item->id]['cat_id'] = $item->cat_id;
            $cart_data[$item->id]['qty'] = $item->qty;
        }
        $total = LaraCart::total($formatted = false, $withDiscount = true);

        return view('frontend.' . config('template') . '.shop.cart.index', ['cart' => $cart_data, 'total' => $total]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \LukePOLO\LaraCart\Exceptions\ModelNotFound
     */
    public function store(Request $request)
    {
//        LaraCart::destroyCart();

        $product_id = $request->product_id;
        $product_qty = $request->qty;

        $item = LaraCart::find(['id' => $product_id]);
        if ($item) {
            LaraCart::updateItem($item->getHash(), 'qty', $item->qty + $product_qty);
        } else {
            $product = Product::where('id', $product_id)->firstOrFail();
            LaraCart::add(
                $product_id,
                $product->title,
                $product_qty,
                $product->cost,
                $options = [
                    'photo' => $product->photo,
                    'slug' => $product->slug,
                    'cat_id' => $product->cat_id,
                ]
            );
        }

        return redirect('cart');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Cart $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = LaraCart::find(['id' => $id]);

        LaraCart::removeItem($item->getHash());
        return redirect('cart');
    }
}
