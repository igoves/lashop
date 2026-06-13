@extends('frontend.layouts.app')

@section('title', $brand->meta_title ?: $brand->title.' — Brands')
@if ($brand->meta_description)
    @section('meta_description', $brand->meta_description)
@endif
@section('canonical', $brand->url())

@section('content')
    <x-breadcrumbs :items="[
        ['title' => 'Brands', 'url' => route('brands.index')],
        ['title' => $brand->title],
    ]" />

    <h1 class="text-2xl font-semibold mb-1">{{ $brand->title }}</h1>
    <p class="text-sm text-gray-500 mb-4">{{ $brand->products_count }} {{ Str::plural('product', $brand->products_count) }}</p>

    @if ($brand->fulldesc)
        <div class="prose prose-gray max-w-none mb-8">
            {!! $brand->fulldesc_html !!}
        </div>
    @endif

    <h2 class="text-lg font-semibold mb-4">Categories with {{ $brand->title }} products</h2>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($categories as $category)
            <a href="{{ $category->url() }}"
               class="block bg-white rounded-lg border border-gray-200 p-4 hover:border-indigo-300 transition">
                @if ($category->logo)
                    <img src="{{ asset('uploads/categories/' . $category->logo) }}" alt="{{ $category->title }}"
                         class="w-full h-24 object-cover rounded mb-2">
                @endif
                <h3 class="font-medium">{{ $category->title }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}</p>
            </a>
        @empty
            <p class="text-gray-500 col-span-full">No categories found.</p>
        @endforelse
    </div>
@endsection
