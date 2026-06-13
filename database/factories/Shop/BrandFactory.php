<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $title = fake()->unique()->company();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'fulldesc' => fake()->optional()->paragraphs(2, true),
        ];
    }
}
