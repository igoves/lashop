<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;

class ImageController extends Controller
{

    public function big ($pic) {
        $size = explode(',', config('image_product_big'));
        return $this->build($pic, (int)$size[0], (int)$size[1]);
    }
    public function medium ($pic) {
        $size = explode(',', config('image_product_medium'));
        return $this->build($pic, (int)$size[0], (int)$size[1]);
    }
    public function small ($pic) {
        $size = explode(',', config('image_product_small'));
        return $this->build($pic, (int)$size[0], (int)$size[1]);
    }

    private function build ($pic, $size_w, $size_h) {
        $img = Image::make('uploads/images/'.$pic.'.jpg')->fit($size_w, $size_h);
        return $img->response('jpg');
    }
}
