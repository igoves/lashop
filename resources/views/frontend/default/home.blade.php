@extends('frontend.'.config('template').'.layouts.app')

@section('content')

    {{ Breadcrumbs::render('home')  }}

    <h3>Bestsellers</h3>

    <div class="row">
    @foreach ($products as $product)
        @include('frontend.'.config('template').'.partials.short_products')
    @endforeach
    </div>
    <br/>
    {!! config('text_on_home') !!}

@endsection