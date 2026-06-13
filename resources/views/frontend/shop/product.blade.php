@extends('frontend.layouts.app')

@section('title', $product->meta_title ?: $product->title)
@if ($product->meta_description)
    @section('meta_description', $product->meta_description)
@endif
@if ($product->meta_keywords)
    @section('meta_keywords', $product->meta_keywords)
@endif
@section('canonical', $product->url())

@section('content')
    <x-breadcrumbs :items="$product->category->ancestorsAndSelf()->map(fn ($c) => [
        'title' => $c->title,
        'url' => $c->url(),
    ])->push(['title' => $product->title])->all()" />

    <div class="grid gap-8 md:grid-cols-2">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            @if ($product->photo)
                <img src="{{ asset(config('shop.image_path').'/big/'.$product->photo) }}"
                     alt="{{ $product->title }}" class="w-full object-cover">
            @else
                <div class="aspect-[4/3] flex items-center justify-center text-gray-400">No photo</div>
            @endif
        </div>

        <div>
            <h1 class="text-2xl font-semibold mb-2">{{ $product->title }}</h1>
            <p class="text-sm text-gray-500 mb-1">
                Category:
                <a href="{{ $product->category->url() }}" class="hover:underline">{{ $product->category->title }}</a>
            </p>
            @if ($product->brand)
                <p class="text-sm text-gray-500 mb-4">
                    Brand:
                    <a href="{{ $product->brand->url() }}" class="hover:underline">{{ $product->brand->title }}</a>
                </p>
            @else
                <div class="mb-4"></div>
            @endif

            <p class="text-3xl font-bold mb-6">${{ price($product->cost) }}</p>

            <form action="{{ route('cart.store') }}" method="POST" class="flex items-center gap-3 mb-8">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <label for="qty" class="text-sm text-gray-600">Qty</label>
                <input id="qty" type="number" name="qty" value="1" min="1" max="999"
                       class="w-20 rounded-md border border-gray-300 px-3 py-2 text-sm">
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700">
                    Add to cart
                </button>
            </form>

            @if ($product->fulldesc)
                <div class="prose prose-gray max-w-none">
                    {!! $product->fulldesc_html !!}
                </div>
            @endif
        </div>
    </div>
@endsection
