<?php

namespace App\Models\Shop;

use Database\Factories\Shop\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    public const STATUS_ACTIVE = 1;

    protected $table = 'shop_products';

    protected $fillable = [
        'cat_id', 'brand_id', 'title', 'slug', 'fulldesc', 'cost', 'photo', 'status',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'status' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        $flush = fn () => Cache::forget('sitemap.xml');

        static::saved($flush);
        static::deleted($flush);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Only products visible on the storefront.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Canonical product URL /{id}-{slug}.
     */
    public function url(): string
    {
        return route('products.show', ['id' => $this->id, 'slug' => $this->slug]);
    }

    public function fulldescHtml(): Attribute
    {
        return Attribute::get(fn () => $this->fulldesc
            ? Str::markdown($this->fulldesc, ['html_input' => 'strip'])
            : '');
    }
}
