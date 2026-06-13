<?php

namespace App\Models\Shop;

use Database\Factories\Shop\BrandFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    protected $table = 'shop_brands';

    protected $fillable = [
        'title', 'slug', 'fulldesc', 'logo',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    public function url(): string
    {
        return route('brands.show', $this->slug);
    }

    public function fulldescHtml(): Attribute
    {
        return Attribute::get(fn () => $this->fulldesc
            ? Str::markdown($this->fulldesc, ['html_input' => 'strip'])
            : '');
    }
}
