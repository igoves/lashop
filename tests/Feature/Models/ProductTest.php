<?php

use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Database\QueryException;

it('belongs to a category', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['cat_id' => $category->id]);

    expect($product->category->is($category))->toBeTrue();
});

it('filters hidden products with the active scope', function () {
    $active = Product::factory()->create();
    Product::factory()->hidden()->create();

    $found = Product::active()->get();

    expect($found)->toHaveCount(1)
        ->and($found->first()->is($active))->toBeTrue()
        ->and($active->isActive())->toBeTrue();
});

it('casts cost to decimal', function () {
    $product = Product::factory()->create(['cost' => 19.5]);

    expect($product->fresh()->cost)->toBe('19.50');
});

it('forbids deleting a category that still has products', function () {
    $product = Product::factory()->create();

    expect(fn () => $product->category->delete())
        ->toThrow(QueryException::class);
});
