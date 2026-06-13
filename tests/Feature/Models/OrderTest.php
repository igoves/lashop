<?php

use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;

it('has items with product, title and price snapshots', function () {
    $order = Order::factory()->create();
    $product = Product::factory()->create(['title' => 'Old Title', 'cost' => 10]);

    $item = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'title' => $product->title,
        'price' => $product->cost,
        'qty' => 2,
    ]);

    $product->update(['title' => 'New Title', 'cost' => 99]);

    $item->refresh();
    expect($order->items->first()->is($item))->toBeTrue()
        ->and($item->product->is($product))->toBeTrue()
        ->and($item->title)->toBe('Old Title')   // snapshot does not change
        ->and($item->price)->toBe('10.00');
});

it('keeps items when the product is deleted (product_id becomes null)', function () {
    $item = OrderItem::factory()->create(['title' => 'Kept']);

    $item->product->delete();

    $item->refresh();
    expect($item->product_id)->toBeNull()
        ->and($item->title)->toBe('Kept');
});

it('deletes items together with the order', function () {
    $item = OrderItem::factory()->create();

    $item->order->delete();

    expect(OrderItem::count())->toBe(0);
});

it('allows an order without email', function () {
    $order = Order::factory()->create(['email' => null]);

    expect($order->fresh()->email)->toBeNull();
});
