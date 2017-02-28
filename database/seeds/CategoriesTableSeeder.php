<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();
        $data = [
            [
                'title' => 'Men',
                'slug' => 'men',
                'desc' => 'Desc men',
                'meta_desc' => 'Meta Desc men',
                'meta_key' => 'Meta Key men',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Women',
                'slug' => 'women',
                'desc' => 'Desc women',
                'meta_desc' => 'Meta Desc women',
                'meta_key' => 'Meta Key women',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
        DB::table('categories')->insert($data);
    }
}
