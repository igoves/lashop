@extends('layouts.app')

@section('title', $categories->title)
@section('meta_desc', $categories->meta_desc)
@section('meta_key', $categories->meta_key)

@section('content')

    <div class="col-md-12">
        {!! Breadcrumbs::render('categories', $categories->title, $categories->slug) !!}
    </div>

    @foreach ($products as $product)
        <div class="col-md-4">
            <div class="thumbnail" style="background: #fff;">
                <a href="/{{ $product->id }}-{{ $product->slug }}" title="{{ $product->name }}">
                    {{ Html::image($product->image, $product->name, ['class'=>'img-responsive']) }}
                </a>
                <div class="caption">
                    <b>
                        {!! link_to_route('products.show', $product->name, [$product->id, $product->slug]) !!}
                    </b>
                    <div class="pull-right">{{ $product->cost }} $</div>
                </div>
            </div>
        </div>
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