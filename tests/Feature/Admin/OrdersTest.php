<?php

use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// --- Guest redirects ---

test('guest cannot access orders index', function () {
    $this->get(route('admin.orders.index'))
        ->assertRedirect(route('login'));
});

test('guest cannot access orders show', function () {
    $order = Order::factory()->create();

    $this->get(route('admin.orders.show', $order))
        ->assertRedirect(route('login'));
});

// --- Non-admin gets 403 ---

test('non-admin cannot access orders index', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.orders.index'))
        ->assertForbidden();
});

test('non-admin cannot access orders show', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $order = Order::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.orders.show', $order))
        ->assertForbidden();
});

// --- Admin access ---

test('admin index shows list of orders with total', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $orders = Order::factory()->count(3)->create();

    $response = $this->actingAs($admin)
        ->get(route('admin.orders.index'))
        ->assertOk();

    foreach ($orders as $order) {
        $response->assertSee($order->name);
        $response->assertSee(number_format($order->total, 2));
    }
});

test('admin show displays order details with items', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    // total=200, item price=50 × qty=2 → subtotal=100 (distinct from total)
    $order = Order::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'comment' => 'Please deliver fast',
        'total' => '200.00',
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'title' => 'Test Widget',
        'price' => '50.00',
        'qty' => 2,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.orders.show', $order))
        ->assertOk();

    $response->assertSee('John Doe');
    $response->assertSee('john@example.com');
    $response->assertSee('+1234567890');
    $response->assertSee('Please deliver fast');
    $response->assertSee(number_format(200.00, 2)); // total
    $response->assertSee('Test Widget');
    $response->assertSee(number_format(50.00, 2));  // unit price
    $response->assertSee('2');                       // qty
    $response->assertSee(number_format(100.00, 2)); // subtotal: 50 * 2 = 100
});

test('admin show displays legacy_order text when not null', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $order = Order::factory()->create([
        'legacy_order' => 'Old order data: product1 x2, product2 x1',
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.orders.show', $order))
        ->assertOk();

    $response->assertSee('Old order data: product1 x2, product2 x1');
    $response->assertSee('legacy');
});
