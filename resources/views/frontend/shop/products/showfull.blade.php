@extends('frontend.layouts.app')

@section('title', $product->title)
@section('meta_desc', $product->meta_desc)
@section('meta_key', $product->meta_key)

@section('content')
{{ Breadcrumbs::render('product_full', $product) }}

<div class="row">
    <div class="col">
        @if ( !empty($product->photo) )
            {{ Html::image('/uploads/'.$product->photo, $product->title, ['class'=>'img-responsive', 'style'=>'max-width:100%;']) }}
        @else
            {{ Html::image('https://dummyimage.com/640x480/000/fff.jpg&text=No+image', $product->title, ['class'=>'img-responsive']) }}
        @endif
    </div>
    <div class="col">
        <h2>{{ $product->title }}</h2>
        <small class="text-muted">Category: {{ $product->categories['title'] }}</small>
        <div><br/>Description<br/>{!! $product->fulldesc !!}</div>
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
    </div>
</div>
@endsection