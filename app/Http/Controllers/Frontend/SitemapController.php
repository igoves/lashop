<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap.xml', now()->addHour(), function () {
            $urls = collect([['loc' => route('home'), 'lastmod' => null]])
                ->concat(Category::all_cached()->map(fn (Category $category) => [
                    'loc' => $category->url(),
                    'lastmod' => $category->updated_at?->toAtomString(),
                ]))
                ->concat(Product::query()->active()->get()->map(fn (Product $product) => [
                    'loc' => $product->url(),
                    'lastmod' => $product->updated_at?->toAtomString(),
                ]))
                ->concat(Page::all_cached()->map(fn (Page $page) => [
                    'loc' => $page->url(),
                    'lastmod' => $page->updated_at?->toAtomString(),
                ]));

            return view('frontend.sitemap', ['urls' => $urls])->render();
        });

        return response($xml)
            ->header('Content-Type', 'application/xml');
    }
}
