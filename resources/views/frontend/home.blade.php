@extends('frontend.layouts.app')

@section('title', setting('meta_title_home', setting('site_name', config('app.name'))))
@section('canonical', route('home'))

@section('content')
    <x-breadcrumbs :items="[]" />

    <h1 class="text-2xl font-semibold mb-4">Bestsellers</h1>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @each('frontend.partials.product-card', $products, 'product')
    </div>

    @if (setting('text_on_home'))
        <div class="prose prose-gray max-w-none mt-8">
            {!! setting('text_on_home') !!} {{-- HTML from settings, edited by admin only --}}
        </div>
    @endif

    @if($recentNews->isNotEmpty())
    <div class="mt-12">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Latest News</h2>
            <a href="{{ route('news.index') }}" class="text-sm text-indigo-600 hover:underline">View all →</a>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($recentNews as $item)
                <a href="{{ $item->url() }}" class="block bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold mb-2">{{ $item->title }}</h3>
                    <p class="text-sm text-gray-500 mb-3">{{ $item->created_at->format('F j, Y') }}</p>
                    <p class="text-sm text-gray-700 line-clamp-3">{{ Str::limit(strip_tags($item->fulldescHtml), 150) }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif
@endsection
