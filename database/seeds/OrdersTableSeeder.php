<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->truncate();
        DB::table('orders')->insert([
            'name' => 'ivanov ivanov',
            'email' => 'adsf@sadf.com',
            'phone' => '0993213131',
            'comment' => 'look at my horse!',
            'order' => 'My first order',
            'total' => 131,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
