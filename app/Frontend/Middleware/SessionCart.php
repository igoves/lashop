<?php
namespace App\Frontend\Middleware;
use Closure;
use Illuminate\Support\Facades\Session;
class SessionCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $cart_qty = 0;
        if ( is_array(Session::get('cart')) ) {
            foreach ( Session::get('cart') as $value ) {
                $cart_qty += $value;
            }
        }
        view()->share('cart_qty', $cart_qty);

        return $next($request);
    }
}