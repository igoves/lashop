<?php

use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

it('has parent and children relations', function () {
    $parent = Category::factory()->create();
    $child = Category::factory()->childOf($parent)->create();

    expect($child->parent->is($parent))->toBeTrue()
        ->and($parent->children->first()->is($child))->toBeTrue();
});

it('has products relation', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['cat_id' => $category->id]);

    expect($category->products->first()->is($product))->toBeTrue();
});

it('builds a nested tree from a single query', function () {
    $rootA = Category::factory()->create(['title' => 'A']);
    $rootB = Category::factory()->create(['title' => 'B']);
    $child = Category::factory()->childOf($rootA)->create(['title' => 'A1']);
    $grandchild = Category::factory()->childOf($child)->create(['title' => 'A1a']);

    $tree = Category::tree();

    expect($tree->pluck('id')->all())->toEqualCanonicalizing([$rootA->id, $rootB->id]);

    $a = $tree->firstWhere('id', $rootA->id);
    expect($a->children->pluck('id')->all())->toBe([$child->id])
        ->and($a->children->first()->children->pluck('id')->all())->toBe([$grandchild->id])
        ->and($tree->firstWhere('id', $rootB->id)->children)->toBeEmpty();
});

it('caches the category list and avoids repeated queries', function () {
    Category::factory()->count(3)->create();

    Category::tree(); // warm up the cache

    DB::enableQueryLog();
    Category::tree();
    expect(DB::getQueryLog())->toBeEmpty();
    DB::disableQueryLog();
});

it('flushes the cache when a category is saved or deleted', function () {
    $first = Category::factory()->create();
    expect(Category::tree())->toHaveCount(1);

    $second = Category::factory()->create();
    expect(Category::tree())->toHaveCount(2);

    $second->delete();
    expect(Category::tree())->toHaveCount(1)
        ->and(Category::tree()->first()->id)->toBe($first->id);
});

it('finds a category by nested slug path', function () {
    $a = Category::factory()->create(['slug' => 'a']);
    $b = Category::factory()->childOf($a)->create(['slug' => 'b']);
    $c = Category::factory()->childOf($b)->create(['slug' => 'c']);

    expect(Category::findByPath('a')->id)->toBe($a->id)
        ->and(Category::findByPath('a/b/c')->id)->toBe($c->id)
        ->and(Category::findByPath('/a/b/')->id)->toBe($b->id);
});

it('returns null for an unknown or non-hierarchical path', function () {
    $a = Category::factory()->create(['slug' => 'a']);
    Category::factory()->create(['slug' => 'x']); // root, not a descendant of a

    expect(Category::findByPath('nope'))->toBeNull()
        ->and(Category::findByPath('a/x'))->toBeNull() // x exists but is not under a
        ->and(Category::findByPath('a/b'))->toBeNull();
});

it('collects descendant ids including its own', function () {
    $root = Category::factory()->create();
    $childA = Category::factory()->childOf($root)->create();
    $childB = Category::factory()->childOf($root)->create();
    $grandchild = Category::factory()->childOf($childA)->create();
    $unrelated = Category::factory()->create();

    expect($root->descendantIds())
        ->toEqualCanonicalizing([$root->id, $childA->id, $childB->id, $grandchild->id])
        ->and($childB->descendantIds())->toBe([$childB->id])
        ->and($root->descendantIds())->not->toContain($unrelated->id);
});

it('builds full slug path and breadcrumbs chain', function () {
    $a = Category::factory()->create(['slug' => 'a']);
    $b = Category::factory()->childOf($a)->create(['slug' => 'b']);
    $c = Category::factory()->childOf($b)->create(['slug' => 'c']);

    expect($c->fullPath())->toBe('a/b/c')
        ->and($a->fullPath())->toBe('a')
        ->and($c->ancestorsAndSelf()->pluck('id')->all())->toBe([$a->id, $b->id, $c->id]);
});

it('forbids deleting a category that still has children', function () {
    $parent = Category::factory()->create();
    Category::factory()->childOf($parent)->create();

    expect(fn () => $parent->delete())
        ->toThrow(QueryException::class);
});
