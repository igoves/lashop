<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        $slug = fake()->unique()->word();

        return [
            'slug' => Str::slug($slug),
            'title' => Str::title($slug),
            'value' => fake()->word(),
        ];
    }
}
