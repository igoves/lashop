@extends('frontend.layouts.app')

@section('title', 'Brands — '.setting('site_name', config('app.name')))

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Brands</h1>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($brands as $brand)
            <a href="{{ $brand->url() }}"
               class="block bg-white rounded-lg border border-gray-200 p-6 hover:border-indigo-300 transition">
                @if ($brand->logo)
                    <img src="{{ asset('uploads/brands/' . $brand->logo) }}" alt="{{ $brand->title }}"
                         class="w-full h-32 object-contain mb-3 rounded">
                @endif
                <h2 class="font-medium text-lg mb-1">{{ $brand->title }}</h2>
                <p class="text-sm text-gray-500">{{ $brand->products_count }} {{ Str::plural('product', $brand->products_count) }}</p>
            </a>
        @empty
            <p class="text-gray-500 col-span-full">No brands found.</p>
        @endforelse
    </div>
@endsection
