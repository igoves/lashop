<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(2, true);

        return [
            'parent_id' => null,
            'title' => Str::title($title),
            'slug' => Str::slug($title),
            'fulldesc' => fake()->paragraph(),
            'logo' => null,
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
        ];
    }

    public function childOf(Category $parent): static
    {
        return $this->state(['parent_id' => $parent->id]);
    }
}
