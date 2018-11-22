@extends('frontend.'.config('template').'.layouts.app')

@section('title', 'Cart')
@section('meta_desc', '')
@section('meta_key', '')

@section('content')
<div class="row">
    <div class="col-md-12">
        {!! Breadcrumbs::render('cart') !!}
    </div>
    <div class="col-md-12">
        @if ( count($cart) > 0 )
            <div class="row">
                <div class="col-md-5">
                    {!! Form::open(['route' => 'orders.store']) !!}
                    @include ('frontend.'.config('template').'.shop.cart.form', ['submitButtonText' => 'Send Order'])
                    {!! Form::close() !!}
                </div>
                <div class="col-md-7">
                    <h3 style="margin-top: 0">Cart</h3>
                    @foreach ($cart as $item)
                        @include ('frontend.'.config('template').'.shop.cart.item', ['item' => $item])
                    @endforeach
                    <h4 class="text-right">Total: {{ $total }} $</h4>
                </div>
            </div>
        @else
            <div class="alert alert-warning text-center">
                Your Cart is empty!
            </div>
        @endif
    </div>
</div>
@endsection