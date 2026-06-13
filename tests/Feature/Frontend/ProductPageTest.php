<?php

use App\Models\Shop\Product;

it('shows an active product by id and slug', function () {
    $product = Product::factory()->create(['slug' => 'cool-thing', 'title' => 'Cool Thing']);

    $this->get("/{$product->id}-cool-thing")
        ->assertOk()
        ->assertSee('Cool Thing');
});

it('redirects an outdated slug to the canonical url with 301', function () {
    $product = Product::factory()->create(['slug' => 'new-slug']);

    $this->get("/{$product->id}-old-slug")
        ->assertStatus(301)
        ->assertRedirect($product->url());
});

it('returns 404 for a hidden product or unknown id', function () {
    $hidden = Product::factory()->hidden()->create(['slug' => 'hidden']);

    $this->get("/{$hidden->id}-hidden")->assertNotFound();
    $this->get('/999999-anything')->assertNotFound();
});
