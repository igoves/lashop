<?php

namespace App\Models\Shop;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

//use App\Models\Shop\Category;

class Category extends Model
{
    use ModelTree, AdminBuilder;
    protected $table = 'shop_categories';

}
