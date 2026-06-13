<?php

use App\Models\Shop\Category;
use App\Models\Shop\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// --- Guest redirects ---

test('guest cannot access categories index', function () {
    $this->get(route('admin.categories.index'))
        ->assertRedirect(route('login'));
});

test('guest cannot access categories create', function () {
    $this->get(route('admin.categories.create'))
        ->assertRedirect(route('login'));
});

test('guest cannot submit categories store', function () {
    $this->post(route('admin.categories.store'), [])
        ->assertRedirect(route('login'));
});

test('guest cannot access categories edit', function () {
    $category = Category::factory()->create();

    $this->get(route('admin.categories.edit', $category))
        ->assertRedirect(route('login'));
});

test('guest cannot submit categories update', function () {
    $category = Category::factory()->create();

    $this->patch(route('admin.categories.update', $category), [])
        ->assertRedirect(route('login'));
});

test('guest cannot submit categories destroy', function () {
    $category = Category::factory()->create();

    $this->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('login'));
});

// --- Non-admin gets 403 ---

test('non-admin cannot access categories index', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.categories.index'))
        ->assertForbidden();
});

test('non-admin cannot access categories create', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.categories.create'))
        ->assertForbidden();
});

test('non-admin cannot submit categories store', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->post(route('admin.categories.store'), [])
        ->assertForbidden();
});

test('non-admin cannot access categories edit', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $category = Category::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.categories.edit', $category))
        ->assertForbidden();
});

test('non-admin cannot submit categories update', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $category = Category::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.categories.update', $category), [])
        ->assertForbidden();
});

test('non-admin cannot submit categories destroy', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $category = Category::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.categories.destroy', $category))
        ->assertForbidden();
});

// --- Admin access ---

test('admin sees all categories on index', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $categories = Category::factory()->count(3)->create();

    $response = $this->actingAs($admin)
        ->get(route('admin.categories.index'))
        ->assertOk();

    foreach ($categories as $category) {
        $response->assertSee($category->title);
        $response->assertSee($category->slug);
    }
});

test('admin can create a root category', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'title' => 'Electronics',
            'slug' => 'electronics',
        ])
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('shop_categories', [
        'slug' => 'electronics',
        'title' => 'Electronics',
        'parent_id' => null,
    ]);
});

test('admin can create a child category', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $parent = Category::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'parent_id' => $parent->id,
            'title' => 'Phones',
            'slug' => 'phones',
        ])
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('shop_categories', [
        'slug' => 'phones',
        'title' => 'Phones',
        'parent_id' => $parent->id,
    ]);
});

test('admin can update a category', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create(['title' => 'Old Title', 'slug' => 'old-slug']);

    $this->actingAs($admin)
        ->patch(route('admin.categories.update', $category), [
            'title' => 'New Title',
            'slug' => 'new-slug',
        ])
        ->assertRedirect(route('admin.categories.index'));

    expect($category->fresh()->title)->toBe('New Title');
    expect($category->fresh()->slug)->toBe('new-slug');
});

test('admin cannot delete a category with children', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $parent = Category::factory()->create();
    Category::factory()->childOf($parent)->create();

    $this->actingAs($admin)
        ->delete(route('admin.categories.destroy', $parent))
        ->assertRedirect()
        ->assertSessionHas('error', 'Cannot delete: has child categories');

    $this->assertDatabaseHas('shop_categories', ['id' => $parent->id]);
});

test('admin cannot delete a category with products', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();
    Product::factory()->create(['cat_id' => $category->id]);

    $this->actingAs($admin)
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect()
        ->assertSessionHas('error', 'Cannot delete: has products');

    $this->assertDatabaseHas('shop_categories', ['id' => $category->id]);
});

test('admin can delete a category without children or products', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseMissing('shop_categories', ['id' => $category->id]);
});

// --- Validation ---

test('store with duplicate slug fails unique validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    Category::factory()->create(['slug' => 'existing-slug']);

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'title' => 'Another Category',
            'slug' => 'existing-slug',
        ])
        ->assertSessionHasErrors('slug');
});

test('store with invalid slug characters fails validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'title' => 'Some Category',
            'slug' => 'Invalid Slug!',
        ])
        ->assertSessionHasErrors('slug');
});

test('update with the same slug does not fail unique validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create(['title' => 'My Category', 'slug' => 'my-category']);

    $this->actingAs($admin)
        ->patch(route('admin.categories.update', $category), [
            'title' => 'My Category Updated',
            'slug' => 'my-category',
        ])
        ->assertRedirect(route('admin.categories.index'));

    expect($category->fresh()->title)->toBe('My Category Updated');
});

test('cannot set category as its own parent', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();

    $this->actingAs($admin)
        ->patch(route('admin.categories.update', $category), [
            'title' => $category->title,
            'slug' => $category->slug,
            'parent_id' => $category->id,
        ])
        ->assertSessionHasErrors('parent_id');
});

test('cannot set a descendant as parent (would create cycle)', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $parent = Category::factory()->create();
    $child = Category::factory()->childOf($parent)->create();

    $this->actingAs($admin)
        ->patch(route('admin.categories.update', $parent), [
            'title' => $parent->title,
            'slug' => $parent->slug,
            'parent_id' => $child->id,
        ])
        ->assertSessionHasErrors('parent_id');
});
