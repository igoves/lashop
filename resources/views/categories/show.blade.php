@extends('layouts.app')

@section('title', $categories->title)
@section('meta_desc', $categories->meta_desc)
@section('meta_key', $categories->meta_key)

@section('content')

    <div class="col-md-12">
        {!! Breadcrumbs::render('categories', $categories->title, $categories->slug) !!}
    </div>

    @foreach ($products as $product)
        @include('partials.short_products')
    @endforeach

    <div class="clearfix"></div>

    <div class="text-center">
        {{ $products->links() }}
    </div>

    @if ( ! isset ($_GET['page']) )
    <div class="col-md-12">
        {{ $categories->desc }}
    </div>
    @endif

@endsection