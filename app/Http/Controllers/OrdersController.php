<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $comment = $request->get('comment');

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

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'comment' => $comment,
            'order' => json_encode($cart_data),
            'total' => $total
        ];

        DB::table('orders')->insert($data);

        $request->session()->forget('cart');

        if ( !empty($email) ) {
            Mail::send('emails.order', $data, function($message) use ($email, $name)
            {
                $message->to($email, $name)->subject('Thanks! Your order');
            });
        }

        return view('orders.success');

    }

}
