@extends('frontend.layouts.app')

@section('title', $categories->title)
@section('meta_desc', $categories->meta_desc)
@section('meta_key', $categories->meta_key)

@section('content')
    {!! Breadcrumbs::render('categories', $categories->title, $categories->slug) !!}

    @if( $category_menu )
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                {!! $category_menu !!}
            </div>
        </div>
        <div class="col-md-9">
            <h3>{{ $categories->title }}</h3>
            <div class="row">
                @foreach ($products as $product)
                    @include('frontend.partials.short_products')
                @endforeach
            </div>
        </div>
    </div>
    @else
        <h3>{{ $categories->title }}</h3>
        <div class="row">
        @foreach ($products as $product)
            @include('frontend.partials.short_products')
        @endforeach
        </div>
    @endif

    <div class="text-center">
        {{ $products->links() }}
    </div>

    @if ( ! isset ($_GET['page']) )
    <br/>
    {!! $categories->logo != '' ? '<img src="/uploads/'.$categories->logo.'" style="max-width:100px; float:left; padding-right:15px;" />' : '' !!}
    {{ $categories->fulldesc }}
    @endif
@endsection