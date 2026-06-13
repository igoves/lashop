<?php

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory;

    public const CACHE_KEY = 'settings.all';

    protected $fillable = ['slug', 'title', 'value'];

    protected static function booted(): void
    {
        $flush = fn () => Cache::forget(self::CACHE_KEY);

        static::saved($flush);
        static::deleted($flush);
    }

    /**
     * Setting value by slug, all settings loaded and cached in one query.
     */
    public static function findBySlug(string $slug, mixed $default = null): mixed
    {
        $all = Cache::remember(self::CACHE_KEY, now()->addDay(),
            fn () => static::query()->pluck('value', 'slug')->all());

        return $all[$slug] ?? $default;
    }
}
