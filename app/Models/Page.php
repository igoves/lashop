<?php

namespace App\Models;

//use App\Models\Page;
//use Encore\Admin\Grid;
//use Encore\Admin\Facades\Admin;
use Encore\Admin\Traits\AdminBuilder;
//use Encore\Admin\Traits\ModelTree;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use AdminBuilder;
    protected $table = 'pages';
}

