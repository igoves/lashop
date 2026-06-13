<?php

use App\Models\Page;
use App\Models\Shop\Category;
use App\Models\Shop\Product;

it('renders root categories and pages in the top menu', function () {
    Category::factory()->create(['title' => 'Men Stuff', 'slug' => 'men']);
    $root = Category::factory()->create(['slug' => 'women']);
    Category::factory()->childOf($root)->create(['title' => 'Sub Hidden', 'slug' => 'sub']);
    Page::factory()->create(['title' => 'About Us', 'slug' => 'about', 'show_in_menu' => true]);

    $this->get('/')
        ->assertOk()
        ->assertSee('Men Stuff')
        ->assertSee('About Us')
        ->assertSee('href="'.url('/men').'"', escape: false)
        ->assertSee('href="'.url('/about.html').'"', escape: false)
        // subcategories are rendered in dropdowns (hidden by CSS)
        ->assertSee('Sub Hidden');
});

it('marks the current category as active in the menu', function () {
    Category::factory()->create(['title' => 'Men Stuff', 'slug' => 'men']);

    $this->get('/men')
        ->assertOk()
        ->assertSeeInOrder(['font-semibold text-indigo-700', 'Men Stuff'], escape: false);
});

it('renders the sidebar category tree on the category page', function () {
    $root = Category::factory()->create(['title' => 'Root Cat', 'slug' => 'root']);
    Category::factory()->childOf($root)->create(['title' => 'Child Cat', 'slug' => 'child']);

    $this->get('/root')
        ->assertOk()
        ->assertSee('Child Cat')
        ->assertSee('href="'.url('/root/child').'"', escape: false);
});

it('renders breadcrumbs with the full ancestor chain', function () {
    $a = Category::factory()->create(['slug' => 'a', 'title' => 'Alpha Cat']);
    $b = Category::factory()->childOf($a)->create(['slug' => 'b', 'title' => 'Beta Cat']);
    $product = Product::factory()->create(['cat_id' => $b->id, 'title' => 'Deep Item', 'slug' => 'deep']);

    $this->get("/{$product->id}-deep")
        ->assertOk()
        ->assertSeeInOrder(['Home', 'Alpha Cat', 'Beta Cat', 'Deep Item']);
});
