<?php

use App\Models\Page;
use App\Models\Setting;
use App\Models\Shop\Category;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\User;

it('creates valid records for every factory', function (string $model) {
    $record = $model::factory()->create();

    expect($record->exists)->toBeTrue();
})->with([
    Category::class,
    Product::class,
    Order::class,
    OrderItem::class,
    Page::class,
    Setting::class,
    User::class,
]);
