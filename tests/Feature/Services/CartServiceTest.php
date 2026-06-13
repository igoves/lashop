<?php

use App\Models\Setting;
use App\Models\Shop\Product;
use App\Services\CartService;

beforeEach(function () {
    $this->cart = app(CartService::class);
});

it('adds a product and increments qty on repeated add', function () {
    $product = Product::factory()->create();

    $this->cart->add($product->id, 2);
    $this->cart->add($product->id);

    expect($this->cart->raw())->toBe([$product->id => 3])
        ->and($this->cart->count())->toBe(3);
});

it('removes a product null-safely', function () {
    $product = Product::factory()->create();
    $this->cart->add($product->id);

    $this->cart->remove($product->id);
    $this->cart->remove(999_999); // non-existent item — no errors

    expect($this->cart->raw())->toBe([])
        ->and($this->cart->isEmpty())->toBeTrue();
});

it('returns items with loaded products and per-line sums', function () {
    $a = Product::factory()->create(['cost' => 10]);
    $b = Product::factory()->create(['cost' => 3.5]);

    $this->cart->add($a->id, 2);
    $this->cart->add($b->id);

    $items = $this->cart->items();

    expect($items)->toHaveCount(2)
        ->and($items->firstWhere('product.id', $a->id)['sum'])->toBe(20.0)
        ->and($items->firstWhere('product.id', $b->id)['sum'])->toBe(3.5)
        ->and($this->cart->total())->toBe(23.5);
});

it('applies the rate setting to sums and total', function () {
    Setting::factory()->create(['slug' => 'rate', 'value' => '2']);
    $product = Product::factory()->create(['cost' => 10]);

    $this->cart->add($product->id, 3);

    expect($this->cart->total())->toBe(60.0) // 10 × 3 × 2
        ->and($this->cart->rate())->toBe(2.0);
});

it('drops deleted and hidden products from items', function () {
    $kept = Product::factory()->create(['cost' => 5]);
    $hidden = Product::factory()->hidden()->create();
    $deleted = Product::factory()->create();

    $this->cart->add($kept->id);
    $this->cart->add($hidden->id);
    $this->cart->add($deleted->id);
    $deleted->delete();

    expect($this->cart->items()->pluck('product.id')->all())->toBe([$kept->id])
        ->and($this->cart->total())->toBe(5.0);
});

it('clears the cart', function () {
    $product = Product::factory()->create();
    $this->cart->add($product->id, 5);

    $this->cart->clear();

    expect($this->cart->raw())->toBe([])
        ->and($this->cart->count())->toBe(0);
});
