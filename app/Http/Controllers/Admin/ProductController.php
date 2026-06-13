<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SaveProductRequest;
use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use App\Services\ProductImageService;

class ProductController extends AdminController
{
    public function __construct(private ProductImageService $imageService) {}

    public function index()
    {
        $query = Product::with('category', 'brand')
            ->orderByDesc('id');

        if ($search = request('search')) {
            $query->where('title', 'like', '%'.addcslashes($search, '%_\\').'%');
        }

        if ($brandId = request('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        if ($categoryId = request('category_id')) {
            $query->where('cat_id', $categoryId);
        }

        $products = $query->paginate(20)->appends(request()->only('search', 'brand_id', 'category_id'));

        $categories = Category::all_cached()->pluck('title', 'id');
        $brands = Brand::orderBy('title')->pluck('title', 'id');

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::all_cached()->pluck('title', 'id');
        $brands = Brand::orderBy('title')->pluck('title', 'id');

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(SaveProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->imageService->store($request->file('photo'));
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all_cached()->pluck('title', 'id');
        $brands = Brand::orderBy('title')->pluck('title', 'id');

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(SaveProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($product->photo) {
                $this->imageService->delete($product->photo);
            }
            $data['photo'] = $this->imageService->store($request->file('photo'));
        } else {
            unset($data['photo']);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->photo) {
            $this->imageService->delete($product->photo);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted.');
    }
}
