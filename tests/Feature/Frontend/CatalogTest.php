<?php

use App\Models\Setting;
use App\Models\Shop\Category;
use App\Models\Shop\Product;

it('shows a category by nested path with products of subcategories', function () {
    $a = Category::factory()->create(['slug' => 'a', 'title' => 'Cat A']);
    $b = Category::factory()->childOf($a)->create(['slug' => 'b']);
    $c = Category::factory()->childOf($b)->create(['slug' => 'c']);

    $direct = Product::factory()->create(['cat_id' => $a->id, 'title' => 'Direct Item']);
    $nested = Product::factory()->create(['cat_id' => $c->id, 'title' => 'Nested Item']);
    $other = Product::factory()->create(['title' => 'Foreign Item']);

    $this->get('/a')
        ->assertOk()
        ->assertSee('Cat A')
        ->assertSee('Direct Item')
        ->assertSee('Nested Item')
        ->assertDontSee('Foreign Item');

    $this->get('/a/b/c')
        ->assertOk()
        ->assertSee('Nested Item')
        ->assertDontSee('Direct Item');
});

it('hides inactive products', function () {
    $category = Category::factory()->create(['slug' => 'cat']);
    Product::factory()->create(['cat_id' => $category->id, 'title' => 'Visible One']);
    Product::factory()->hidden()->create(['cat_id' => $category->id, 'title' => 'Hidden One']);

    $this->get('/cat')
        ->assertOk()
        ->assertSee('Visible One')
        ->assertDontSee('Hidden One');
});

it('returns 404 for an unknown path', function () {
    Category::factory()->create(['slug' => 'real']);

    $this->get('/unknown')->assertNotFound();
    $this->get('/real/unknown')->assertNotFound();
});

it('paginates products by the products_count setting', function () {
    Setting::factory()->create(['slug' => 'products_count', 'value' => '2']);
    $category = Category::factory()->create(['slug' => 'cat']);
    Product::factory()->count(3)->create(['cat_id' => $category->id]);

    $response = $this->get('/cat')->assertOk();

    expect($response->viewData('products'))
        ->count()->toBe(2)
        ->total()->toBe(3);

    $this->get('/cat?page=2')->assertOk();
});

it('sorts products by whitelisted fields', function () {
    $category = Category::factory()->create(['slug' => 'cat']);
    Product::factory()->create(['cat_id' => $category->id, 'title' => 'Bravo', 'cost' => 5]);
    Product::factory()->create(['cat_id' => $category->id, 'title' => 'Alpha', 'cost' => 9]);

    expect($this->get('/cat?sort=cost&dir=desc')->viewData('products')->first()->cost)
        ->toBe('9.00')
        ->and($this->get('/cat?sort=title&dir=asc')->viewData('products')->first()->title)
        ->toBe('Alpha');

    // non-whitelisted field is ignored without error
    $this->get('/cat?sort=id;drop&dir=asc')->assertOk();
});
