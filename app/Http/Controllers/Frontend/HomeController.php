<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Shop\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('frontend.home', [
            'products' => Product::query()->active()->latest('id')->limit(3)->get(),
            'recentNews' => News::query()->where('status', 1)->latest('created_at')->limit(3)->get(),
        ]);
    }
}
