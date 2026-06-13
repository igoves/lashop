@extends('frontend.layouts.app')

@section('title', 'News — '.setting('site_name', config('app.name')))

@section('content')
    <h1 class="text-2xl font-semibold mb-6">News</h1>

    @if($news->isEmpty())
        <p class="text-gray-500">No news articles yet.</p>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($news as $item)
                <a href="{{ $item->url() }}" class="block bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition">
                    <h2 class="text-lg font-semibold mb-2">{{ $item->title }}</h2>
                    <p class="text-sm text-gray-500 mb-3">{{ $item->created_at->format('F j, Y') }}</p>
                    <p class="text-sm text-gray-700 line-clamp-3">{{ Str::limit(strip_tags($item->fulldescHtml), 150) }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $news->links() }}
        </div>
    @endif
@endsection
