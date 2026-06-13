<?php

use App\Mail\OrderConfirmation;
use App\Models\Setting;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\Mail;

function fillCart(array $products): void
{
    $cart = app(CartService::class);
    foreach ($products as [$product, $qty]) {
        $cart->add($product->id, $qty);
    }
}

it('creates an order with items from the cart and clears it', function () {
    Mail::fake();
    $a = Product::factory()->create(['title' => 'Alpha', 'cost' => 10]);
    $b = Product::factory()->create(['title' => 'Beta', 'cost' => 2.5]);
    fillCart([[$a, 2], [$b, 1]]);

    $this->post('/orders', [
        'name' => 'John',
        'email' => 'john@example.com',
        'phone' => '+100000000',
        'comment' => 'Fast please',
    ])->assertRedirect(route('orders.success'));

    $order = Order::sole();
    expect((float) $order->total)->toBe(22.5)
        ->and($order->items)->toHaveCount(2)
        ->and($order->items->firstWhere('title', 'Alpha'))
        ->qty->toBe(2)->price->toBe('10.00')->product_id->toBe($a->id)
        ->and(app(CartService::class)->isEmpty())->toBeTrue();

    $this->get('/order/success')->assertOk()->assertSee('Thank you');
});

it('applies the rate to the order total but keeps raw prices in items', function () {
    Mail::fake();
    Setting::factory()->create(['slug' => 'rate', 'value' => '2']);
    $product = Product::factory()->create(['cost' => 10]);
    fillCart([[$product, 3]]);

    $this->post('/orders', ['name' => 'John', 'phone' => '1'])
        ->assertRedirect(route('orders.success'));

    $order = Order::sole();
    expect((float) $order->total)->toBe(60.0) // 10 × 3 × 2
        ->and($order->items->first()->price)->toBe('10.00'); // snapshot, before rate
});

it('sends a confirmation email when email is provided', function () {
    Mail::fake();
    $product = Product::factory()->create();
    fillCart([[$product, 1]]);

    $this->post('/orders', [
        'name' => 'John',
        'email' => 'john@example.com',
        'phone' => '+1',
    ]);

    Mail::assertSent(OrderConfirmation::class,
        fn (OrderConfirmation $mail) => $mail->hasTo('john@example.com'));
});

it('does not send an email when none is provided', function () {
    Mail::fake();
    $product = Product::factory()->create();
    fillCart([[$product, 1]]);

    $this->post('/orders', ['name' => 'John', 'phone' => '+1']);

    expect(Order::count())->toBe(1);
    Mail::assertNothingSent();
});

it('refuses to create an order from an empty cart', function () {
    Mail::fake();

    $this->post('/orders', ['name' => 'John', 'phone' => '+1'])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHasErrors('cart');

    expect(Order::count())->toBe(0);
    Mail::assertNothingSent();
});

it('validates order fields', function (array $payload, string $field) {
    $product = Product::factory()->create();
    fillCart([[$product, 1]]);

    $this->post('/orders', $payload)->assertSessionHasErrors($field);

    expect(Order::count())->toBe(0);
})->with([
    'missing name' => [['phone' => '+1'], 'name'],
    'missing phone' => [['name' => 'John'], 'phone'],
    'bad email' => [['name' => 'John', 'phone' => '+1', 'email' => 'not-an-email'], 'email'],
]);

it('throttles order submissions', function () {
    Mail::fake();
    $product = Product::factory()->create();

    foreach (range(1, 5) as $i) {
        fillCart([[$product, 1]]);
        $this->post('/orders', ['name' => 'John', 'phone' => '+1']);
    }

    fillCart([[$product, 1]]);
    $this->post('/orders', ['name' => 'John', 'phone' => '+1'])
        ->assertStatus(429);
});

it('renders the email with items, qty and rated total', function () {
    Setting::factory()->create(['slug' => 'rate', 'value' => '2']);
    $product = Product::factory()->create(['title' => 'Alpha', 'cost' => 10]);
    $order = Order::factory()->create(['total' => 60]);
    $order->items()->create([
        'product_id' => $product->id,
        'title' => 'Alpha',
        'price' => 10,
        'qty' => 3,
    ]);

    $html = (new OrderConfirmation($order))->render();

    expect($html)
        ->toContain('Alpha')
        ->toContain('>3<')      // qty is present
        ->toContain('60.00');   // total with rate
});
