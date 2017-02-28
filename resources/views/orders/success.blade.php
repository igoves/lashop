@extends('layouts.app')

@section('title', 'Order Success')

@section('content')
    <div class="col-md-12">
        {!! Breadcrumbs::render('order') !!}
    </div>
    <div class="col-md-12">
        <div class="alert alert-success">
            <h1>Thanks for order!</h1>
            {{ link_to_route('home', '<< Back to home') }}
        </div>
    </div>
@endsection