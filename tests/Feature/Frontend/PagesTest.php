<?php

use App\Models\Page;

it('shows a static page with the site layout', function () {
    Page::factory()->create([
        'slug' => 'about',
        'title' => 'About Us',
        'fulldesc' => "## Who we are\n\nFriendly **team**.",
    ]);

    $this->get('/about.html')
        ->assertOk()
        ->assertSee('About Us')
        ->assertSee('Who we are')
        ->assertSee('<strong>team</strong>', escape: false)
        ->assertSee('</footer>', escape: false); // page is rendered inside the main layout
});

it('returns 404 for an unknown page', function () {
    $this->get('/missing.html')->assertNotFound();
});
