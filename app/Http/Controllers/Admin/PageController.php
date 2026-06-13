<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SavePageRequest;
use App\Models\Page;

class PageController extends AdminController
{
    public function index()
    {
        $query = Page::orderBy('title');

        if ($search = request('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $pages = $query->get();

        return view('admin.pages.index', compact('pages', 'search'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(SavePageRequest $request)
    {
        $data = $request->validated();
        $positions = $data['show_in_menu'] ?? [];

        Page::create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'show_in_menu' => in_array('menu', $positions),
            'show_in_footer' => in_array('footer', $positions),
            'fulldesc' => $data['fulldesc'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(SavePageRequest $request, Page $page)
    {
        $data = $request->validated();
        $positions = $data['show_in_menu'] ?? [];

        $page->update([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'show_in_menu' => in_array('menu', $positions),
            'show_in_footer' => in_array('footer', $positions),
            'fulldesc' => $data['fulldesc'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted.');
    }
}
