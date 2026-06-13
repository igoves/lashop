<?php

namespace App\Models\Shop;

use Database\Factories\Shop\CategoryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    public const CACHE_KEY = 'shop.categories.all';

    protected $table = 'shop_categories';

    protected $fillable = [
        'parent_id', 'title', 'slug', 'fulldesc', 'logo',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected static function booted(): void
    {
        $flush = function () {
            Cache::forget(self::CACHE_KEY);
            Cache::forget('sitemap.xml');
        };

        static::saved($flush);
        static::deleted($flush);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'cat_id');
    }

    /**
     * All categories in one query, cached.
     *
     * @return Collection<int, static>
     */
    public static function all_cached(): Collection
    {
        return Cache::remember(self::CACHE_KEY, now()->addDay(),
            fn () => static::query()->orderBy('title')->get());
    }

    /**
     * Category tree: root nodes with recursively populated
     * children relation (no extra queries).
     *
     * @return Collection<int, static>
     */
    public static function tree(): Collection
    {
        $all = static::all_cached();
        $byParent = $all->groupBy('parent_id');

        $all->each(function (self $category) use ($byParent) {
            $category->setRelation(
                'children',
                $byParent->get($category->id, new Collection)
            );
        });

        return $byParent->get('', new Collection)->values();
    }

    /**
     * Category by nested slug path "a/b/c" (or null).
     */
    public static function findByPath(string $path): ?static
    {
        $all = static::all_cached();
        $byKey = $all->keyBy(fn (self $c) => ($c->parent_id ?? '').'/'.$c->slug);

        $current = null;
        foreach (explode('/', trim($path, '/')) as $slug) {
            $key = ($current?->id ?? '').'/'.$slug;
            $current = $byKey->get($key);

            if ($current === null) {
                return null;
            }
        }

        return $current;
    }

    /**
     * IDs of this category and all its descendants (for product queries).
     *
     * @return array<int, int>
     */
    public function descendantIds(): array
    {
        $byParent = static::all_cached()->groupBy('parent_id');

        $ids = [];
        $queue = [$this->id];

        while ($queue !== []) {
            $id = array_shift($queue);
            $ids[] = $id;

            foreach ($byParent->get($id, collect()) as $child) {
                $queue[] = $child->id;
            }
        }

        return $ids;
    }

    /**
     * Chain from root to this category (for URL path and breadcrumbs).
     *
     * @return Collection<int, static>
     */
    public function ancestorsAndSelf(): Collection
    {
        $all = static::all_cached()->keyBy('id');

        $chain = [];
        $current = $all->get($this->id);

        while ($current !== null) {
            array_unshift($chain, $current);
            $current = $current->parent_id ? $all->get($current->parent_id) : null;
        }

        return new Collection($chain);
    }

    /**
     * Full slug path "a/b/c".
     */
    public function fullPath(): string
    {
        return $this->ancestorsAndSelf()->pluck('slug')->implode('/');
    }

    public function url(): string
    {
        return route('categories.show', ['path' => $this->fullPath()]);
    }
}
