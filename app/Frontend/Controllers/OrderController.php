<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $comment = $request->comment;
        $cart = $request->session()->get('cart');
//        $cart_data = [];
        $cart_html = '';
        $total = 0;
        if ( \count($cart) ) {
            $cart_ids = implode(', ', array_keys($cart));
            $cart_ids = explode(',', $cart_ids);
            $cart_array = DB::table('shop_products')->whereIn('id', $cart_ids)->get();
            foreach ( $cart_array as $key => $value ) {
//                $cart_data[$value->id]['id'] = $value->id;
//                $cart_data[$value->id]['title'] = $value->title;
//                $cart_data[$value->id]['photo'] = $value->photo;
//                $cart_data[$value->id]['slug'] = $value->slug;
//                $cart_data[$value->id]['cost'] = $value->cost;
//                $cart_data[$value->id]['qty'] = $cart[$value->id];
                $cart_html .= $value->id.' - '.$value->title.' - '.$value->cost.'<br/>';
                $total += $value->cost*$cart[$value->id];
            }
        }
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'comment' => $comment,
//            'order' => json_encode($cart_data),
            'order' => $cart_html,
            'total' => $total,
            'created_at' =>  \Carbon\Carbon::now(), # \Datetime()
            'updated_at' => \Carbon\Carbon::now(),  # \Datetime()
        ];
        DB::table('shop_orders')->insert($data);
        $request->session()->forget('cart');
        if ( !empty($email) ) {
            Mail::send('frontend.shop.emails.your_order', $data, function($message) use ($email, $name)
            {
                $message->to($email, $name)->subject('Thanks! Your order');
            });
        }
        return view('frontend.shop.order_success');
    }
}