<?php

namespace App\Models;

use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class News extends Model
{
    /** @use HasFactory<NewsFactory> */
    use HasFactory;

    public const CACHE_KEY = 'news.all';

    protected $fillable = [
        'title', 'slug', 'fulldesc', 'status',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        $flush = function () {
            Cache::forget(self::CACHE_KEY);
            Cache::forget('sitemap.xml');
        };

        static::saved($flush);
        static::deleted($flush);
    }

    /**
     * All published news, cached.
     *
     * @return Collection<int, static>
     */
    public static function all_cached()
    {
        return Cache::remember(self::CACHE_KEY, now()->addDay(),
            fn () => static::query()->where('status', 1)->orderByDesc('created_at')->get());
    }

    public function url(): string
    {
        return route('news.show', $this->slug);
    }

    public function fulldescHtml(): Attribute
    {
        return Attribute::get(fn () => $this->fulldesc
            ? Str::markdown($this->fulldesc, ['html_input' => 'strip'])
            : '');
    }
}
