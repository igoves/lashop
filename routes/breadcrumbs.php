<?php

// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('home'));
});

// Category
Breadcrumbs::register('categories', function($breadcrumbs, $title, $slug)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push($title, route('categories.show', $slug));
});

// Product full
Breadcrumbs::register('product_full', function($breadcrumbs, $product)
{
    $breadcrumbs->parent('categories', $product->cat['title'], $product->cat['slug']);
    $breadcrumbs->push($product->name);
});

// Cart
Breadcrumbs::register('cart', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Cart', route('cart.index'));
});

// Pages
Breadcrumbs::register('pages', function($breadcrumbs, $pages)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push($pages->title, route('cart.index'));
});

// Search
Breadcrumbs::register('search', function($breadcrumbs, $title)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push($title);
});

// Order Success
Breadcrumbs::register('order', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Order Success');
});