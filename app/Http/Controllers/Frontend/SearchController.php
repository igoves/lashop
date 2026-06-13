<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * POST /search (header form) → GET /search/{story}.
     */
    public function store(Request $request): RedirectResponse
    {
        $story = trim((string) $request->input('story', ''));

        if ($story === '') {
            return redirect()->route('home');
        }

        return redirect()->route('search.index', ['story' => $story]);
    }

    /**
     * GET /search/{story}.
     */
    public function index(string $story): View|RedirectResponse
    {
        $story = trim($story);

        if ($story === '') {
            return redirect()->route('home');
        }

        // escape LIKE special chars so %/_ in the query are treated as literals
        $like = '%'.addcslashes($story, '%_\\').'%';

        $products = Product::query()->active()
            ->where(fn ($query) => $query
                ->whereRaw("title LIKE ? ESCAPE '\\'", [$like])
                ->orWhereRaw("fulldesc LIKE ? ESCAPE '\\'", [$like]))
            ->orderBy('title')
            ->paginate((int) setting('search_count', 6))
            ->withQueryString();

        return view('frontend.search', [
            'products' => $products,
            'phrase' => $story,
        ]);
    }
}
