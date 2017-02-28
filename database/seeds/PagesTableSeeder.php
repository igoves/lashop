<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->truncate();
        $data = [
            [
                'title' => 'About',
                'slug' => 'about',
                'desc' => 'Desc about. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.',
                'meta_desc' => 'Meta Desc about',
                'meta_key' => 'Meta Key about',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Contacts',
                'slug' => 'contacts',
                'desc' => 'Desc contacts. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.',
                'meta_desc' => 'Meta Desc contacts',
                'meta_key' => 'Meta Key contacts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
        DB::table('pages')->insert($data);
    }
}
