<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart');
        $cart_data = [];
        $total = 0;
        if ( count($cart) ) {
            $cart_ids = implode(", ", array_keys($cart));
            $cart_ids = explode(',', $cart_ids);
            $cart_array = DB::table('products')->whereIn('id', $cart_ids)->get();
            foreach ( $cart_array as $key => $value ) {
                $cart_data[$value->id]['id'] = $value->id;
                $cart_data[$value->id]['name'] = $value->name;
                $cart_data[$value->id]['image'] = $value->image;
                $cart_data[$value->id]['slug'] = $value->slug;
                $cart_data[$value->id]['cost'] = $value->cost;
                $cart_data[$value->id]['qty'] = $cart[$value->id];
                $total += $value->cost*$cart[$value->id];
            }
        }
        return view('cart.index', ['cart' => $cart_data, 'total' => $total ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_id = $request->input('product_id');
        $qty = (int)$request->input('qty');

        $value = session()->get("cart.$product_id");
        if ( $value ) {
            $qty = $value+$qty;
        }

        $request->session()->put("cart.$product_id", $qty);

        return redirect('cart');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        session()->forget("cart.$id");
        return redirect('cart');
    }
}
