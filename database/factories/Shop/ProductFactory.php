<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'cat_id' => Category::factory(),
            'title' => Str::title($title),
            'slug' => Str::slug($title),
            'fulldesc' => fake()->paragraphs(2, true),
            'cost' => fake()->randomFloat(2, 1, 1000),
            'photo' => null,
            'status' => Product::STATUS_ACTIVE,
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
        ];
    }

    public function hidden(): static
    {
        return $this->state(['status' => 0]);
    }
}
