@extends('layouts.app')

@section('content')

    <div class="col-md-12">
        {!! Breadcrumbs::render($breadcrumbs) !!}
    </div>

    <div class="col-md-12">
        <h3 style="margin-top: 0;">Bestsellers</h3>
    </div>

    @foreach ($products as $product)
        @include('partials.short_products')
    @endforeach

    <div class="clearfix"></div>

    <div class="col-md-12">
        {!! Config::get('settings.start_text') !!}
    </div>

@endsection