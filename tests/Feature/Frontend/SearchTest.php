<?php

use App\Models\Shop\Product;

it('finds products by title and fulldesc', function () {
    Product::factory()->create(['title' => 'Red Bicycle', 'fulldesc' => 'plain']);
    Product::factory()->create(['title' => 'Plain Thing', 'fulldesc' => 'a red saddle inside']);
    Product::factory()->create(['title' => 'Blue Car', 'fulldesc' => 'nothing']);

    $this->get('/search/red')
        ->assertOk()
        ->assertSee('Red Bicycle')
        ->assertSee('Plain Thing')
        ->assertDontSee('Blue Car');
});

it('hides inactive products from search', function () {
    Product::factory()->hidden()->create(['title' => 'Red Hidden Thing']);

    $this->get('/search/red')
        ->assertOk()
        ->assertDontSee('Red Hidden Thing');
});

it('redirects the search form post to the pretty url', function () {
    $this->post('/search', ['story' => 'red bike'])
        ->assertRedirect(route('search.index', ['story' => 'red bike']));
});

it('redirects an empty query to home', function () {
    $this->post('/search', ['story' => '  '])->assertRedirect(route('home'));
    $this->get('/search/%20')->assertRedirect(route('home'));
});

it('escapes LIKE wildcards so % and _ are literal', function () {
    Product::factory()->create(['title' => 'Discount 100% off', 'fulldesc' => '-']);
    Product::factory()->create(['title' => 'Plain Product', 'fulldesc' => '-']);

    // "%" is literal: finds only the product with a percent sign in the title
    $this->get('/search/'.urlencode('100%'))
        ->assertOk()
        ->assertSee('Discount 100% off')
        ->assertDontSee('Plain Product');

    // a bare "_" does not act as a single-character wildcard
    $this->get('/search/'.urlencode('Pl_in'))
        ->assertOk()
        ->assertDontSee('Plain Product');
});
