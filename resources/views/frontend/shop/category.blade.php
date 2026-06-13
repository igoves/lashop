@extends('frontend.layouts.app')

@section('title', $category->meta_title ?: $category->title)
@if ($category->meta_description)
    @section('meta_description', $category->meta_description)
@endif
@if ($category->meta_keywords)
    @section('meta_keywords', $category->meta_keywords)
@endif
@section('canonical', $category->url())

@section('content')
    <x-breadcrumbs :items="$category->ancestorsAndSelf()->map(fn ($c) => [
        'title' => $c->title,
        'url' => $c->url(),
    ])->all()" />

    <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
        <aside aria-label="Filters" class="space-y-4">
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-3">Categories</h3>
                <ul class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100 text-sm">
                    @foreach ($categoryTree as $root)
                        @include('frontend.partials.category-tree-item', ['item' => $root, 'depth' => 0, 'current' => $category, 'ancestorIds' => $ancestorIds])
                    @endforeach
                </ul>
            </div>

            @if ($brands->isNotEmpty())
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-3">Brands</h3>
                    <form method="GET" action="{{ $category->url() }}" class="bg-white rounded-lg border border-gray-200 p-3 text-sm">
                        @foreach ($brands as $brand)
                            <label class="flex items-center gap-2 py-1 cursor-pointer">
                                <input type="checkbox" name="brand[]" value="{{ $brand->id }}"
                                       {{ $selectedBrands->contains($brand->id) ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                <span class="flex-1">{{ $brand->title }}</span>
                                <span class="text-gray-400 text-xs">{{ $brand->products_count }}</span>
                            </label>
                        @endforeach

                        @if ($selectedBrands->isNotEmpty())
                            <a href="{{ $category->url() }}" class="block mt-2 text-xs text-gray-500 hover:underline">Clear all</a>
                        @endif
                    </form>
                </div>
            @endif
        </aside>

        <section>
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h1 class="text-2xl font-semibold">{{ $category->title }}</h1>

                <nav class="text-sm text-gray-500 flex items-center gap-2" aria-label="Sorting">
                    <span>Sort by:</span>
                    @foreach (['cost' => 'price', 'title' => 'name'] as $field => $label)
                        @php($active = $sort === $field)
                        @php($nextDir = $active && $dir === 'asc' ? 'desc' : 'asc')
                        <a href="{{ $category->url() }}?sort={{ $field }}&dir={{ $nextDir }}"
                           @class(['hover:underline', 'font-semibold text-gray-900' => $active])>
                            {{ $label }}@if($active) {{ $dir === 'asc' ? '↑' : '↓' }}@endif
                        </a>
                    @endforeach
                </nav>
            </div>

            @if ($products->isEmpty())
                <p class="text-gray-500">No products in this category yet.</p>
            @else
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @each('frontend.partials.product-card', $products, 'product')
                </div>
                <div class="mt-6">{{ $products->links() }}</div>
            @endif

            @if ($category->fulldesc)
                <p class="text-gray-600 mt-8">{{ $category->fulldesc }}</p>
            @endif
        </section>
    </div>
@endsection
