<?php

namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /** Allowed sort fields: query param => column. */
    private const SORTABLE = ['cost' => 'cost', 'title' => 'title'];

    public function show(Request $request, string $path): View
    {
        $category = Category::findByPath($path) ?? abort(404);

        $query = Product::query()->active()
            ->whereIn('cat_id', $category->descendantIds());

        $selectedBrands = collect($request->query('brand', []))
            ->map(fn ($v) => (int) $v)
            ->filter()
            ->values();

        if ($selectedBrands->isNotEmpty()) {
            $query->whereIn('brand_id', $selectedBrands);
        }

        [$sort, $dir] = $this->sorting($request);

        $query->orderBy(self::SORTABLE[$sort] ?? 'id', $dir);

        $allBrandCounts = Product::active()
            ->whereIn('cat_id', $category->descendantIds())
            ->select('brand_id', DB::raw('count(*) as products_count'))
            ->groupBy('brand_id')
            ->pluck('products_count', 'brand_id');

        $brandCounts = $allBrandCounts;

        if ($selectedBrands->isNotEmpty()) {
            $coveredIds = Product::active()
                ->whereIn('cat_id', $category->descendantIds())
                ->whereIn('brand_id', $selectedBrands)
                ->pluck('id');

            $uncoveredCounts = Product::active()
                ->whereIn('cat_id', $category->descendantIds())
                ->whereNotIn('id', $coveredIds)
                ->select('brand_id', DB::raw('count(*) as products_count'))
                ->groupBy('brand_id')
                ->pluck('products_count', 'brand_id');

            $brandCounts = $allBrandCounts->map(fn ($count, $id) =>
                $selectedBrands->contains($id)
                    ? $count
                    : ($uncoveredCounts[$id] ?? 0)
            );
        }

        $brands = Brand::whereIn('id', $allBrandCounts->keys())
            ->orderBy('title')
            ->get()
            ->each(fn (Brand $b) => $b->products_count = $brandCounts[$b->id] ?? 0);

        $counts = Product::active()
            ->select('cat_id', DB::raw('count(*) as products_count'))
            ->groupBy('cat_id')
            ->pluck('products_count', 'cat_id');

        $tree = Category::tree();
        $this->attachCounts($tree, $counts);

        return view('frontend.shop.category', [
            'category' => $category,
            'ancestorIds' => $category->ancestorsAndSelf()->pluck('id')->toArray(),
            'categoryTree' => $tree,
            'brands' => $brands,
            'selectedBrands' => $selectedBrands,
            'products' => $query
                ->paginate((int) setting('products_count', 6))
                ->withQueryString(),
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    /**
     * Sorting from query string with field whitelist.
     *
     * @return array{0: ?string, 1: string}
     */
    private function sorting(Request $request): array
    {
        $sort = (string) $request->query('sort', '');
        $dir = $request->query('dir') === 'desc' ? 'desc' : 'asc';

        return [array_key_exists($sort, self::SORTABLE) ? $sort : null, $dir];
    }

    private function attachCounts($nodes, $counts): int
    {
        $total = 0;

        foreach ($nodes as $node) {
            $node->products_count = $counts[$node->id] ?? 0;

            if ($node->children->isNotEmpty()) {
                $node->products_count += $this->attachCounts($node->children, $counts);
            }

            $total += $node->products_count;
        }

        return $total;
    }
}
