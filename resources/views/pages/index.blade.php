@extends('layouts.app')

@section('title', $pages->title)
@section('meta_desc', $pages->meta_desc)
@section('meta_key', $pages->meta_key)

@section('content')

    <div class="col-md-12">
        {!! Breadcrumbs::render('pages', $pages) !!}
    </div>

    <div class="col-md-12">
        <h1 style="margin-top: 0;">{{ $pages->title }}</h1>
        {{ $pages->desc }}
    </div>

@endsection