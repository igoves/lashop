@extends('frontend.'.config('template').'.layouts.app')

@section('title', 'Search Result')

@section('content')
<div class="row">
    <div class="col-md-12">
        {!! Breadcrumbs::render('search', 'Search Result') !!}
    </div>

    <div class="col-md-12">
        <h3>
            @if ( count($products) > 0 )
                Found: {{ count($products) }}
            @else
                Search Result
            @endif
        </h3>
    </div>

    @if ( count($products) > 0 )

        @foreach ($products as $product)
            @include('frontend.'.config('template').'.partials.short_products')
        @endforeach

        <div class="clearfix"></div>

        <div class="text-center">
            {{ $products->links() }}
        </div>
    @else
        <div class="col-md-12">
            <div class="alert alert-warning text-center">
                Nothing found
            </div>
        </div>
    @endif
</div>
@endsection