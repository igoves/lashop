<?php

use App\Models\Page;
use App\Models\Shop\Category;
use App\Models\Shop\Product;

it('renders meta tags and canonical on the product page', function () {
    $product = Product::factory()->create([
        'slug' => 'thing',
        'meta_title' => 'Thing | Buy cheap',
        'meta_description' => 'Best thing ever',
        'meta_keywords' => 'thing, buy',
    ]);

    $this->get("/{$product->id}-thing")
        ->assertOk()
        ->assertSee('<title>Thing | Buy cheap</title>', escape: false)
        ->assertSee('name="description" content="Best thing ever"', escape: false)
        ->assertSee('name="keywords" content="thing, buy"', escape: false)
        ->assertSee('rel="canonical" href="'.$product->url().'"', escape: false);
});

it('falls back to the entity title when meta_title is empty', function () {
    $category = Category::factory()->create(['slug' => 'cat', 'title' => 'Plain Cat']);

    $this->get('/cat')
        ->assertOk()
        ->assertSee('<title>Plain Cat</title>', escape: false)
        ->assertSee('rel="canonical" href="'.url('/cat').'"', escape: false);
});

it('renders meta on static pages', function () {
    Page::factory()->create([
        'slug' => 'about',
        'meta_title' => 'About meta',
        'meta_description' => 'About description',
    ]);

    $this->get('/about.html')
        ->assertOk()
        ->assertSee('<title>About meta</title>', escape: false)
        ->assertSee('content="About description"', escape: false);
});

it('lists all active entities in sitemap.xml', function () {
    $a = Category::factory()->create(['slug' => 'a']);
    $b = Category::factory()->childOf($a)->create(['slug' => 'b']);
    $product = Product::factory()->create(['cat_id' => $b->id, 'slug' => 'item']);
    $hidden = Product::factory()->hidden()->create(['slug' => 'ghost']);
    Page::factory()->create(['slug' => 'about']);

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee(route('home'))
        ->assertSee(url('/a'))
        ->assertSee(url('/a/b'))
        ->assertSee($product->url())
        ->assertSee(url('/about.html'))
        ->assertDontSee("{$hidden->id}-ghost");
});
