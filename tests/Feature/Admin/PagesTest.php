<?php

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// --- Guest redirects ---

test('guest cannot access pages index', function () {
    $this->get(route('admin.pages.index'))
        ->assertRedirect(route('login'));
});

test('guest cannot access pages create', function () {
    $this->get(route('admin.pages.create'))
        ->assertRedirect(route('login'));
});

test('guest cannot submit pages store', function () {
    $this->post(route('admin.pages.store'), [])
        ->assertRedirect(route('login'));
});

test('guest cannot access pages edit', function () {
    $page = Page::factory()->create();

    $this->get(route('admin.pages.edit', $page))
        ->assertRedirect(route('login'));
});

test('guest cannot submit pages update', function () {
    $page = Page::factory()->create();

    $this->patch(route('admin.pages.update', $page), [])
        ->assertRedirect(route('login'));
});

test('guest cannot submit pages destroy', function () {
    $page = Page::factory()->create();

    $this->delete(route('admin.pages.destroy', $page))
        ->assertRedirect(route('login'));
});

// --- Non-admin gets 403 ---

test('non-admin cannot access pages index', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.pages.index'))
        ->assertForbidden();
});

test('non-admin cannot access pages create', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.pages.create'))
        ->assertForbidden();
});

test('non-admin cannot submit pages store', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->post(route('admin.pages.store'), [])
        ->assertForbidden();
});

test('non-admin cannot access pages edit', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $page = Page::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.pages.edit', $page))
        ->assertForbidden();
});

test('non-admin cannot submit pages update', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $page = Page::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.pages.update', $page), [])
        ->assertForbidden();
});

test('non-admin cannot submit pages destroy', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $page = Page::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.pages.destroy', $page))
        ->assertForbidden();
});

// --- Admin access ---

test('admin sees all pages on index', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $pages = Page::factory()->count(3)->create();

    $response = $this->actingAs($admin)
        ->get(route('admin.pages.index'))
        ->assertOk();

    foreach ($pages as $page) {
        $response->assertSee($page->title);
        $response->assertSee($page->slug);
    }
});

test('admin can view pages create form', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.pages.create'))
        ->assertOk()
        ->assertSee('Create Page');
});

test('admin can create a page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), [
            'title' => 'About Us',
            'slug' => 'about-us',
        ])
        ->assertRedirect(route('admin.pages.index'));

    $this->assertDatabaseHas('pages', ['slug' => 'about-us', 'title' => 'About Us']);
});

test('admin can view pages edit form', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $page = Page::factory()->create(['title' => 'My Page', 'slug' => 'my-page']);

    $this->actingAs($admin)
        ->get(route('admin.pages.edit', $page))
        ->assertOk()
        ->assertSee('My Page')
        ->assertSee('my-page');
});

test('admin can update a page', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $page = Page::factory()->create(['title' => 'Old Title', 'slug' => 'old-slug']);

    $this->actingAs($admin)
        ->patch(route('admin.pages.update', $page), [
            'title' => 'New Title',
            'slug' => 'new-slug',
        ])
        ->assertRedirect(route('admin.pages.index'));

    expect($page->fresh()->title)->toBe('New Title');
    expect($page->fresh()->slug)->toBe('new-slug');
});

test('admin can delete a page', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $page = Page::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.pages.destroy', $page))
        ->assertRedirect(route('admin.pages.index'));

    $this->assertDatabaseMissing('pages', ['id' => $page->id]);
});

// --- Validation ---

test('store without title fails validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['slug' => 'some-slug'])
        ->assertSessionHasErrors('title');
});

test('store without slug fails validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['title' => 'Some Title'])
        ->assertSessionHasErrors('slug');
});

test('store with duplicate slug fails unique validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    Page::factory()->create(['slug' => 'existing-slug']);

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), [
            'title' => 'Another Page',
            'slug' => 'existing-slug',
        ])
        ->assertSessionHasErrors('slug');
});

test('update with the same slug does not fail unique validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $page = Page::factory()->create(['title' => 'My Page', 'slug' => 'my-page']);

    $this->actingAs($admin)
        ->patch(route('admin.pages.update', $page), [
            'title' => 'My Page Updated',
            'slug' => 'my-page',
        ])
        ->assertRedirect(route('admin.pages.index'));

    expect($page->fresh()->title)->toBe('My Page Updated');
});

test('store with invalid slug characters fails validation', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), [
            'title' => 'Some Page',
            'slug' => 'Invalid Slug!',
        ])
        ->assertSessionHasErrors('slug');
});
