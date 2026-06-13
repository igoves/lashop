<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    public const CACHE_KEY = 'pages.all';

    protected $fillable = [
        'title', 'slug', 'show_in_menu', 'show_in_footer', 'fulldesc',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'show_in_menu' => 'boolean',
            'show_in_footer' => 'boolean',
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
     * All pages in one query, cached (for the menu).
     *
     * @return Collection<int, static>
     */
    public static function all_cached(): Collection
    {
        return Cache::remember(self::CACHE_KEY, now()->addDay(),
            fn () => static::query()->orderBy('title')->get());
    }

    public function url(): string
    {
        return route('pages.index', ['slug' => $this->slug]);
    }

    public function fulldescHtml(): Attribute
    {
        return Attribute::get(fn () => $this->fulldesc
            ? Str::markdown($this->fulldesc, ['html_input' => 'strip'])
            : '');
    }
}
