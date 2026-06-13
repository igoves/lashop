<?php

namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Mail\OrderConfirmation;
use App\Models\Shop\Order;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->withErrors(['cart' => __('Your cart is empty.')]);
        }

        $rate = $this->cart->rate();

        /** @var Order $order */
        $order = DB::transaction(function () use ($request, $items, $rate) {
            $validated = $request->validated();

            if (Auth::check()) {
                $userId = Auth::id();
            } elseif (! empty($validated['email'])) {
                $user = User::firstOrCreate(
                    ['email' => $validated['email']],
                    ['name' => $validated['name'], 'password' => Hash::make(Str::random(32))]
                );
                $userId = $user->id;
            } else {
                $userId = null;
            }

            $order = Order::create([
                ...$validated,
                'user_id' => $userId,
                'status' => 'New',
                // Σ cost × qty × rate
                'total' => round($items->sum(
                    fn (array $item) => (float) $item['product']->cost * $item['qty'] * $rate
                ), 2),
            ]);

            $order->items()->createMany($items->map(fn (array $item) => [
                'product_id' => $item['product']->id,
                'title' => $item['product']->title,   // snapshot
                'price' => $item['product']->cost,    // snapshot, before rate
                'qty' => $item['qty'],
            ]));

            return $order;
        });

        $this->cart->clear();

        if ($order->email !== null) {
            // send after the response is sent: user doesn't wait for SMTP
            defer(fn () => Mail::to($order->email, $order->name)
                ->send(new OrderConfirmation($order)));
        }

        return redirect()->route('orders.success');
    }

    public function success(): View
    {
        return view('frontend.shop.order_success');
    }
}
