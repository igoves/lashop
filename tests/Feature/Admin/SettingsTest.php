<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

// --- Guest redirects ---

test('guest cannot access settings index', function () {
    $this->get(route('admin.settings.index'))
        ->assertRedirect(route('login'));
});

test('guest cannot access settings edit', function () {
    $setting = Setting::factory()->create();

    $this->get(route('admin.settings.edit', $setting))
        ->assertRedirect(route('login'));
});

test('guest cannot submit settings update', function () {
    $setting = Setting::factory()->create();

    $this->patch(route('admin.settings.update', $setting), ['value' => 'new'])
        ->assertRedirect(route('login'));
});

// --- Non-admin gets 403 ---

test('non-admin cannot access settings index', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.settings.index'))
        ->assertForbidden();
});

test('non-admin cannot access settings edit', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $setting = Setting::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.settings.edit', $setting))
        ->assertForbidden();
});

test('non-admin cannot submit settings update', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $setting = Setting::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.settings.update', $setting), ['value' => 'new'])
        ->assertForbidden();
});

// --- Admin access ---

test('admin sees all settings on index page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    // Seed known settings
    $this->artisan('settings:seed');

    $response = $this->actingAs($admin)
        ->get(route('admin.settings.index'))
        ->assertOk();

    $response->assertSee('Text on Home');
    $response->assertSee('Products per Page');
    $response->assertSee('Contact Email');
    $response->assertSee('Big (WxH)');
});

test('admin can view settings edit form', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $setting = Setting::factory()->create(['value' => 'original']);

    $this->actingAs($admin)
        ->get(route('admin.settings.edit', $setting))
        ->assertOk()
        ->assertSee($setting->slug)
        ->assertSee('original');
});

test('admin can update a setting and cache is cleared', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $setting = Setting::factory()->create(['value' => 'old-value']);

    // Warm up the cache
    Cache::put(Setting::CACHE_KEY, [$setting->slug => 'old-value'], now()->addDay());
    expect(Cache::has(Setting::CACHE_KEY))->toBeTrue();

    $this->actingAs($admin)
        ->patch(route('admin.settings.update', $setting), ['value' => 'new-value'])
        ->assertRedirect(route('admin.settings.index'));

    // Value persisted in DB
    expect($setting->fresh()->value)->toBe('new-value');

    // Cache flushed by model observer
    expect(Cache::has(Setting::CACHE_KEY))->toBeFalse();
});

test('admin can set setting value to null', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $setting = Setting::factory()->create(['value' => 'something']);

    $this->actingAs($admin)
        ->patch(route('admin.settings.update', $setting), ['value' => null])
        ->assertRedirect(route('admin.settings.index'));

    expect($setting->fresh()->value)->toBeNull();
});
