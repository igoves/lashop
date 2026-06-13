<?php

use App\Models\Shop\Category;

it('returns a partial for fullajax requests', function () {
    Category::factory()->create(['slug' => 'cat', 'title' => 'Some Cat']);

    $response = $this->get('/cat', ['X-Fullajax' => 'true'])->assertOk();
    $html = $response->getContent();

    expect($html)
        ->toContain('<title>')
        ->toContain('Some Cat')
        ->not->toContain('<!DOCTYPE html>')
        ->not->toContain('</footer>');
});

it('returns the full page for regular requests', function () {
    Category::factory()->create(['slug' => 'cat', 'title' => 'Some Cat']);

    $response = $this->get('/cat')->assertOk();

    expect($response->getContent())
        ->toContain('<!DOCTYPE html>')
        ->toContain('</footer>')
        ->toContain('Some Cat');
});
