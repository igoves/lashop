@extends('frontend.layouts.app')

@section('title', $page->meta_title ?: $page->title.' — '.setting('site_name', config('app.name')))
@if ($page->meta_description)
    @section('meta_description', $page->meta_description)
@endif
@if ($page->meta_keywords)
    @section('meta_keywords', $page->meta_keywords)
@endif
@section('canonical', $page->url())

@section('content')
    <x-breadcrumbs :items="[['title' => $page->title]]" />

    <article class="max-w-3xl">
        <h1 class="text-2xl font-semibold mb-4">{{ $page->title }}</h1>
        <div class="prose prose-gray max-w-none">
            {!! $page->fulldesc_html !!}
        </div>
    </article>
@endsection
