@extends('frontend.layouts.app')

@section('title', 'Search: '.$phrase.' — '.setting('site_name', config('app.name')))

@section('content')
    <x-breadcrumbs :items="[['title' => 'Search']]" />

    <h1 class="text-2xl font-semibold mb-4">Search results for “{{ $phrase }}”</h1>

    @if ($products->isEmpty())
        <p class="text-gray-500">Nothing found.</p>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @each('frontend.partials.product-card', $products, 'product')
        </div>
        <div class="mt-6">{{ $products->links() }}</div>
    @endif
@endsection
