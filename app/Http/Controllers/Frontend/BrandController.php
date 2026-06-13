<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::orderBy('title')->withCount('products')->get();

        return view('frontend.brands.index', compact('brands'));
    }

    public function show(string $slug): View
    {
        $brand = Brand::where('slug', $slug)->withCount('products')->firstOrFail();

        $productsByCategory = $brand->products()
            ->active()
            ->select('cat_id', \DB::raw('count(*) as products_count'))
            ->groupBy('cat_id')
            ->pluck('products_count', 'cat_id');

        $categories = Category::whereIn('id', $productsByCategory->keys())
            ->get()
            ->each(fn (Category $c) => $c->products_count = $productsByCategory[$c->id])
            ->values();

        return view('frontend.brands.show', compact('brand', 'categories'));
    }
}
