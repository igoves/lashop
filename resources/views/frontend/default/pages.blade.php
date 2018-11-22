@extends('frontend.'.config('template').'.layouts.app')

@section('title', $pages->title)
@section('meta_desc', $pages->meta_desc)
@section('meta_key', $pages->meta_key)

@section('content')
{{ Breadcrumbs::render('pages', $pages) }}
<h1>{{ $pages->title }}</h1>
{!! $pages->fulldesc !!}
@endsection