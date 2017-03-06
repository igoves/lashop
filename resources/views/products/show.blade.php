@extends('layouts.app')

@section('title', $product->name)
@section('meta_desc', $product->meta_desc)
@section('meta_key', $product->meta_key)

@section('content')

    <div class="col-md-12">
        {!! Breadcrumbs::render('product_full', $product) !!}
    </div>

    <div class="col-md-6">
        @if ( !empty($product->image) )
            {{ Html::image($product->image, $product->name, ['class'=>'img-responsive']) }}
        @else
            {{ Html::image('https://dummyimage.com/640x480/000/fff.jpg&text=No+image', $product->name, ['class'=>'img-responsive']) }}
        @endif

    </div>
    <div class="col-md-6">
        <h2>{{ $product->name }}</h2>
        <small class="text-muted">Category: {!! link_to_route('categories.show', $product->cat['title'], $product->cat['slug']) !!}</small>
        <div><br/>Description<br/>{!! $product->desc !!}</div>
        <hr/>

        {{ Form::open(['route' => 'cart.store']) }}
        <div class="row">
            <div class="col-md-3">
                <b>COST:</b> {{ $product->cost }} $
            </div>
            <div class="col-md-3">
                {{ Form::text('qty', 1, ['class' => 'form-control text-center']) }}
            </div>
            <div class="col-md-6">
                {{ Form::submit('Buy', ['class' => 'btn btn-primary btn-block']) }}
            </div>
        </div>
        {{ Form::hidden('product_id', $product->id) }}
        {{ Form::close() }}

        <hr/>
        {{--{!! link_to_route('products.index', '<< Back') !!}--}}
    </div>

@endsection