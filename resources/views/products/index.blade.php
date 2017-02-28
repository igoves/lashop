@extends('layouts.app')

@section('content')

    <div class="col-md-12">
        {!! Breadcrumbs::render($breadcrumbs) !!}
    </div>

    @foreach ($products as $product)
        @include('partials.short_products')
    @endforeach

    <div class="clearfix"></div>

    <div class="text-center">
        {{ $products->links() }}
    </div>

@endsection