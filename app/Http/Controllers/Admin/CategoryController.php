<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SaveCategoryRequest;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends AdminController
{
    public function __construct(private ImageService $imageService) {}

    public function index()
    {
        $search = request('search');
        $productCounts = Product::query()
            ->selectRaw('cat_id, count(*) as products_count')
            ->groupBy('cat_id')
            ->pluck('products_count', 'cat_id')
            ->all();

        $tree = Category::tree();

        return view('admin.categories.index', compact('tree', 'productCounts', 'search'));
    }

    public function create()
    {
        $parentOptions = $this->buildParentOptions(Category::all_cached());

        return view('admin.categories.create', compact('parentOptions'));
    }

    public function store(SaveCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->imageService->store($request->file('logo'), 'categories');
        } else {
            $data['logo'] = null;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        $excluded = $category->descendantIds();
        $allCategories = Category::all_cached()->filter(fn ($c) => ! in_array($c->id, $excluded, true));
        $parentOptions = $this->buildParentOptions($allCategories);

        return view('admin.categories.edit', compact('category', 'parentOptions'));
    }

    public function update(SaveCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($category->logo) {
                $this->imageService->delete($category->logo, 'categories');
            }
            $data['logo'] = $this->imageService->store($request->file('logo'), 'categories');
        } else {
            unset($data['logo']);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->exists()) {
            return back()->with('error', 'Cannot delete: has child categories');
        }

        if ($category->products()->exists()) {
            return back()->with('error', 'Cannot delete: has products');
        }

        if ($category->logo) {
            $this->imageService->delete($category->logo, 'categories');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted.');
    }

    private function buildParentOptions(Collection $categories): array
    {
        $byParent = $categories->groupBy('parent_id');
        $options = [];

        $traverse = function ($parentId, int $depth) use (&$traverse, $byParent, &$options): void {
            foreach ($byParent->get($parentId, collect()) as $category) {
                $options[$category->id] = str_repeat("\u{00A0}\u{00A0}", $depth).$category->title;
                $traverse($category->id, $depth + 1);
            }
        };

        $traverse('', 0);

        return $options;
    }
}
