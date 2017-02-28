@extends('layouts.app')

@section('title', 'Search Result')

@section('content')

    <div class="col-md-12">
        {!! Breadcrumbs::render('search', 'Search Result') !!}
    </div>

    <div class="col-md-12">
        <h3 style="margin-top: 0;">
            @if ( count($products) > 0 )
                Found: {{ count($products) }}
            @else
                Search Result
            @endif
        </h3>
    </div>

    @if ( count($products) > 0 )
        @foreach ($products as $product)
            @include('partials.short_products')
        @endforeach

        <div class="clearfix"></div>

        <div class="text-center">
            {{ $products->links() }}
        </div>
    @else
        <div class="clearfix"></div>
        <div class="alert alert-warning text-center">
            Nothing found
        </div>
    @endif

@endsection