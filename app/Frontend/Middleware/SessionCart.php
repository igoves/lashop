<?php

namespace App\Frontend\Middleware;

use Closure;
use Illuminate\Http\Request;
use LukePOLO\LaraCart\Facades\LaraCart;

class SessionCart
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $qty_total = 0;
        foreach ($items = LaraCart::getItems() as $item) {
            $qty_total += $item->qty;
        }
        view()->share('cart_qty', $qty_total);
        return $next($request);
    }
}
