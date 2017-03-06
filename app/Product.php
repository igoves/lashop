<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public function cat()
    {
        return $this->belongsTo('App\Category', 'cat_id');
    }

}
