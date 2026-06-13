<?php

use App\Models\Shop\Category;
use App\Models\Shop\Product;
use App\Models\User;
use App\Services\ProductImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

// --- Guest redirects ---

test('guest cannot access products index', function () {
    $this->get(route('admin.products.index'))
        ->assertRedirect(route('login'));
});

test('guest cannot access products create', function () {
    $this->get(route('admin.products.create'))
        ->assertRedirect(route('login'));
});

test('guest cannot submit products store', function () {
    $this->post(route('admin.products.store'), [])
        ->assertRedirect(route('login'));
});

test('guest cannot access products edit', function () {
    $product = Product::factory()->create();

    $this->get(route('admin.products.edit', $product))
        ->assertRedirect(route('login'));
});

test('guest cannot submit products update', function () {
    $product = Product::factory()->create();

    $this->patch(route('admin.products.update', $product), [])
        ->assertRedirect(route('login'));
});

test('guest cannot submit products destroy', function () {
    $product = Product::factory()->create();

    $this->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('login'));
});

// --- Non-admin gets 403 ---

test('non-admin cannot access products index', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.products.index'))
        ->assertForbidden();
});

test('non-admin cannot access products create', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.products.create'))
        ->assertForbidden();
});

test('non-admin cannot submit products store', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->post(route('admin.products.store'), [])
        ->assertForbidden();
});

test('non-admin cannot access products edit', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.products.edit', $product))
        ->assertForbidden();
});

test('non-admin cannot submit products update', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.products.update', $product), [])
        ->assertForbidden();
});

test('non-admin cannot submit products destroy', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.products.destroy', $product))
        ->assertForbidden();
});

// --- Admin access ---

test('admin index lists products with pagination', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $products = Product::factory()->count(3)->create();

    $response = $this->actingAs($admin)
        ->get(route('admin.products.index'))
        ->assertOk();

    foreach ($products as $product) {
        $response->assertSee($product->title);
    }
});

test('admin can create a product without photo', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'cat_id' => $category->id,
            'title' => 'Test Product',
            'slug' => 'test-product',
            'cost' => '99.99',
            'status' => '1',
        ])
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('shop_products', [
        'slug' => 'test-product',
        'title' => 'Test Product',
        'photo' => null,
    ]);
});

test('admin can upload photo when creating product', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();

    $this->mock(ProductImageService::class, function ($mock) {
        $mock->shouldReceive('store')->once()->andReturn('test-photo.jpg');
    });

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'cat_id' => $category->id,
            'title' => 'Photo Product',
            'slug' => 'photo-product',
            'cost' => '50.00',
            'status' => '1',
            'photo' => UploadedFile::fake()->image('product.jpg', 100, 100),
        ])
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('shop_products', [
        'slug' => 'photo-product',
        'photo' => 'test-photo.jpg',
    ]);
});

test('admin can update a product', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();
    $product = Product::factory()->create(['title' => 'Old Title', 'slug' => 'old-slug']);

    $this->actingAs($admin)
        ->patch(route('admin.products.update', $product), [
            'cat_id' => $category->id,
            'title' => 'New Title',
            'slug' => 'new-slug',
            'cost' => '199.00',
            'status' => '1',
        ])
        ->assertRedirect(route('admin.products.index'));

    expect($product->fresh()->title)->toBe('New Title');
    expect($product->fresh()->slug)->toBe('new-slug');
});

test('admin can delete a product', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $product = Product::factory()->create(['photo' => null]);

    $this->actingAs($admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseMissing('shop_products', ['id' => $product->id]);
});

test('admin delete removes old photo files', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $product = Product::factory()->create(['photo' => 'old-photo.jpg']);

    $this->mock(ProductImageService::class, function ($mock) {
        $mock->shouldReceive('delete')->once()->with('old-photo.jpg');
    });

    $this->actingAs($admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseMissing('shop_products', ['id' => $product->id]);
});

test('admin update replaces old photo when new one uploaded', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();
    $product = Product::factory()->create(['photo' => 'old-photo.jpg']);

    $this->mock(ProductImageService::class, function ($mock) {
        $mock->shouldReceive('delete')->once()->with('old-photo.jpg');
        $mock->shouldReceive('store')->once()->andReturn('new-photo.jpg');
    });

    $this->actingAs($admin)
        ->patch(route('admin.products.update', $product), [
            'cat_id' => $category->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'cost' => $product->cost,
            'status' => $product->status,
            'photo' => UploadedFile::fake()->image('new.jpg', 100, 100),
        ])
        ->assertRedirect(route('admin.products.index'));

    expect($product->fresh()->photo)->toBe('new-photo.jpg');
});

// --- Validation ---

test('store with no title fails validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'cat_id' => $category->id,
            'slug' => 'no-title',
            'cost' => '10.00',
            'status' => '1',
        ])
        ->assertSessionHasErrors('title');
});

test('store with no cat_id fails validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'title' => 'Some Product',
            'slug' => 'some-product',
            'cost' => '10.00',
            'status' => '1',
        ])
        ->assertSessionHasErrors('cat_id');
});

test('store with negative cost fails validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'cat_id' => $category->id,
            'title' => 'Product',
            'slug' => 'product',
            'cost' => '-5',
            'status' => '1',
        ])
        ->assertSessionHasErrors('cost');
});

test('store with invalid file type fails validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'cat_id' => $category->id,
            'title' => 'Product',
            'slug' => 'product',
            'cost' => '10.00',
            'status' => '1',
            'photo' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ])
        ->assertSessionHasErrors('photo');
});

test('update with the same slug does not fail unique validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();
    $product = Product::factory()->create(['title' => 'My Product', 'slug' => 'my-product']);

    $this->actingAs($admin)
        ->patch(route('admin.products.update', $product), [
            'cat_id' => $category->id,
            'title' => 'My Product Updated',
            'slug' => 'my-product',
            'cost' => $product->cost,
            'status' => $product->status,
        ])
        ->assertRedirect(route('admin.products.index'));

    expect($product->fresh()->title)->toBe('My Product Updated');
});
