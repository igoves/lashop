@extends('frontend.layouts.app')

@section('content')

    {{ Breadcrumbs::render('home')  }}

    <h3>Bestsellers</h3>

    <div class="row">
    @foreach ($products as $product)
        @include('frontend.partials.short_products')
    @endforeach
    </div>
    <br/>
    {!! config('text_on_home') !!}

@endsection