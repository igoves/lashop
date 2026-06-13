<?php

namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View
    {
        return view('frontend.shop.cart', [
            'items' => $this->cart->items(),
            'total' => $this->cart->total(),
        ]);
    }

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $this->cart->add(
            (int) $request->validated('product_id'),
            (int) ($request->validated('qty') ?? 1),
        );

        return redirect()->route('cart.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->cart->remove($id); // missing item is silently ignored

        return redirect()->route('cart.index');
    }
}
