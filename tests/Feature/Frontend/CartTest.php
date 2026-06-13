<?php

use App\Models\Shop\Product;
use App\Services\CartService;

it('shows an empty cart', function () {
    $this->get('/cart')
        ->assertOk()
        ->assertSee('Your cart is empty');
});

it('adds a product to the cart and redirects to it', function () {
    $product = Product::factory()->create(['cost' => 10]);

    $this->post('/cart', ['product_id' => $product->id, 'qty' => 2])
        ->assertRedirect(route('cart.index'));

    $this->get('/cart')
        ->assertOk()
        ->assertSee($product->title)
        ->assertSee('× 2', escape: false);
});

it('increments qty when the same product is added twice', function () {
    $product = Product::factory()->create();

    $this->post('/cart', ['product_id' => $product->id, 'qty' => 1]);
    $this->post('/cart', ['product_id' => $product->id, 'qty' => 2]);

    expect(app(CartService::class)->raw())->toBe([$product->id => 3]);
});

it('shows the items count in the header', function () {
    $product = Product::factory()->create();

    $this->post('/cart', ['product_id' => $product->id, 'qty' => 3]);

    $this->get('/')
        ->assertOk()
        ->assertSeeInOrder(['data-cart-count', '3'], escape: false);
});

it('removes an item from the cart', function () {
    $product = Product::factory()->create();
    $this->post('/cart', ['product_id' => $product->id, 'qty' => 1]);

    $this->delete("/cart/{$product->id}")
        ->assertRedirect(route('cart.index'));

    expect(app(CartService::class)->isEmpty())->toBeTrue();
});

it('removing a missing item does not fail', function () {
    $this->delete('/cart/999999')->assertRedirect(route('cart.index'));
});

it('rejects invalid qty', function (mixed $qty) {
    $product = Product::factory()->create();

    $this->from('/cart')
        ->post('/cart', ['product_id' => $product->id, 'qty' => $qty])
        ->assertSessionHasErrors('qty');
})->with([0, -1, 'abc', 1000]);

it('rejects a missing or hidden product', function () {
    $hidden = Product::factory()->hidden()->create();

    $this->post('/cart', ['product_id' => 999999, 'qty' => 1])
        ->assertSessionHasErrors('product_id');

    $this->post('/cart', ['product_id' => $hidden->id, 'qty' => 1])
        ->assertSessionHasErrors('product_id');
});
