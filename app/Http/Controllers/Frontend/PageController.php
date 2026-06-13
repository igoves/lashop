<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(string $slug): View
    {
        $page = Page::query()->where('slug', $slug)->firstOrFail();

        return view('frontend.page', compact('page'));
    }
}
