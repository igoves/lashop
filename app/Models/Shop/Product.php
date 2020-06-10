<?php

namespace App\Models\Shop;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use ModelTree, AdminBuilder;
    protected $table = 'shop_products';

    public function categories()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }

}
