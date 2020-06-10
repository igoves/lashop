<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $pages = Page::where('slug', $slug)->first();
        return view('frontend.' . config('template') . '.pages', compact('pages'));
    }
}
