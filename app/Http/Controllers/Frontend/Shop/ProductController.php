<?php

namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(int $id, string $slug): View|RedirectResponse
    {
        $product = Product::query()->active()
            ->with('category')
            ->findOrFail($id);

        // stale slug → 301 to canonical URL (preserves SEO link equity)
        if ($product->slug !== $slug) {
            return redirect()->to($product->url(), 301);
        }

        return view('frontend.shop.product', compact('product'));
    }
}
