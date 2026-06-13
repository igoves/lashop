<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $news = News::query()->where('status', 1)->orderByDesc('created_at')->paginate(12);

        return view('frontend.news.index', compact('news'));
    }

    public function show(string $slug): View
    {
        $news = News::where('slug', $slug)->where('status', 1)->firstOrFail();

        return view('frontend.news.show', compact('news'));
    }
}
