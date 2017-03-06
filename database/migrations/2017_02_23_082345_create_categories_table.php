<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->text('desc');
            $table->string('meta_desc');
            $table->string('meta_key');
            $table->timestamps();
        });

//        Schema::table('products', function (Blueprint $table) {
//            $table->integer('cat_id')->unsigned()->index()->default(1);
//            $table->foreign('cat_id')
//                ->references('id')
//                ->on('categories');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }
}
