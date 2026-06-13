<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop\Order;
use App\Models\Shop\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends AdminController
{
    public function index()
    {
        $query = Order::orderByDesc('created_at');

        if ($search = request('search')) {
            $like = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like);
            });
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20)->appends(request()->only('search', 'status'));

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view('admin.orders.show', compact('order'));
    }

    public function create(): View
    {
        $products = Product::query()->active()->orderBy('title')->get();

        return view('admin.orders.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'comment' => ['nullable', 'string', 'max:5000'],
            'delivery_method' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.product_id' => ['required', 'exists:shop_products,id'],
            'lines.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $rate = (float) setting('rate', 1);

        /** @var Order $order */
        $order = DB::transaction(function () use ($validated, $rate) {
            $order = Order::create([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'],
                'comment' => $validated['comment'] ?? null,
                'delivery_method' => $validated['delivery_method'] ?? null,
                'payment_method' => $validated['payment_method'] ?? null,
                'total' => 0,
                'status' => 'New',
            ]);

            $total = 0;

            foreach ($validated['lines'] as $line) {
                $product = Product::findOrFail($line['product_id']);
                $subtotal = round((float) $product->cost * $line['qty'] * $rate, 2);
                $total += $subtotal;

                $order->items()->create([
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->cost,
                    'qty' => $line['qty'],
                ]);
            }

            $order->update(['total' => $total]);

            return $order;
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order #'.$order->id.' created.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order #'.$order->id.' deleted.');
    }

    public function edit(Order $order): View
    {
        $order->load('items');
        $products = Product::query()->active()->orderBy('title')->get();

        return view('admin.orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'comment' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'string', 'max:255'],
            'delivery_method' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.product_id' => ['required', 'exists:shop_products,id'],
            'lines.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $rate = (float) setting('rate', 1);

        DB::transaction(function () use ($validated, $order, $rate) {
            $order->update([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'],
                'comment' => $validated['comment'] ?? null,
                'status' => $validated['status'],
                'delivery_method' => $validated['delivery_method'] ?? null,
                'payment_method' => $validated['payment_method'] ?? null,
            ]);

            $order->items()->delete();

            $total = 0;

            foreach ($validated['lines'] as $line) {
                $product = Product::findOrFail($line['product_id']);
                $subtotal = round((float) $product->cost * $line['qty'] * $rate, 2);
                $total += $subtotal;

                $order->items()->create([
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->cost,
                    'qty' => $line['qty'],
                ]);
            }

            $order->update(['total' => $total]);
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order #'.$order->id.' updated.');
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'max:255'],
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Order status updated.');
    }
}
