<?php

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

it('returns a setting value via helper with default fallback', function () {
    Setting::factory()->create(['slug' => 'rate', 'value' => '2']);

    expect(setting('rate'))->toBe('2')
        ->and(setting('missing', 'fallback'))->toBe('fallback')
        ->and(setting('missing'))->toBeNull();
});

it('loads all settings with a single cached query', function () {
    Setting::factory()->create(['slug' => 'a', 'value' => '1']);
    Setting::factory()->create(['slug' => 'b', 'value' => '2']);

    setting('a'); // warm up the cache

    DB::enableQueryLog();
    expect(setting('a'))->toBe('1')
        ->and(setting('b'))->toBe('2');
    expect(DB::getQueryLog())->toBeEmpty();
    DB::disableQueryLog();
});

it('flushes the cache when a setting changes', function () {
    $setting = Setting::factory()->create(['slug' => 'rate', 'value' => '2']);
    expect(setting('rate'))->toBe('2');

    $setting->update(['value' => '3']);
    expect(setting('rate'))->toBe('3');

    $setting->delete();
    expect(setting('rate', 'gone'))->toBe('gone');
});
