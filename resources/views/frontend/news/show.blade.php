@extends('frontend.layouts.app')

@section('title', $news->meta_title ?: $news->title.' — '.setting('site_name', config('app.name')))

@if($news->meta_description)
@section('meta_description', $news->meta_description)
@endif

@if($news->meta_keywords)
@section('meta_keywords', $news->meta_keywords)
@endif

@section('canonical', $news->url())

@section('content')
    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ route('home') }}" class="hover:underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('news.index') }}" class="hover:underline">News</a>
        <span class="mx-1">/</span>
        <span class="text-gray-900">{{ $news->title }}</span>
    </nav>

    <article class="prose prose-gray max-w-none">
        <h1>{{ $news->title }}</h1>
        <p class="text-sm text-gray-500">{{ $news->created_at->format('F j, Y') }}</p>
        <div class="mt-4">
            {!! $news->fulldescHtml !!}
        </div>
    </article>
@endsection
