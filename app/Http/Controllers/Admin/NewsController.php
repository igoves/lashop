<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SaveNewsRequest;
use App\Models\News;

class NewsController extends AdminController
{
    public function index()
    {
        $query = News::orderByDesc('created_at');

        if ($search = request('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $news = $query->get();

        return view('admin.news.index', compact('news', 'search'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(SaveNewsRequest $request)
    {
        News::create($request->validated());

        return redirect()->route('admin.news.index')
            ->with('success', 'News created.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(SaveNewsRequest $request, News $news)
    {
        $news->update($request->validated());

        return redirect()->route('admin.news.index')
            ->with('success', 'News updated.');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'News deleted.');
    }
}
