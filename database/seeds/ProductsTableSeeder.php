<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->truncate();

        $faker = Faker::create();
        foreach (range(1,99) as $index) {
            $name = $faker->name;
            DB::table('products')->insert([
                'cat_id' => rand(1,2),
                'name' => $name,
                'slug' => str_slug($name, '-'),
                'desc' => $faker->text(200),
                'image' => $faker->imageUrl($width = 640, $height = 480),
                'cost' => $faker->numberBetween($min = 100, $max = 999),
                'meta_desc' => $faker->text(200),
                'meta_key' => $faker->words(10, true),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

    }
}
