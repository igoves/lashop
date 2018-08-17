@extends('frontend.layouts.app')

@section('title', 'Order Success')

@section('content')
{!! Breadcrumbs::render('order') !!}

<div class="alert alert-success">
    <h1>Thanks for order!</h1>
    {{ link_to_route('home', '<< Back to home') }}
</div>
@endsection