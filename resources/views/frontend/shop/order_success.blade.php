@extends('frontend.layouts.app')

@section('title', 'Order placed — '.setting('site_name', config('app.name')))

@section('content')
    <div class="max-w-xl mx-auto text-center py-16">
        <p class="text-5xl mb-4">✅</p>
        <h1 class="text-2xl font-semibold mb-2">Thank you! Your order has been placed.</h1>
        <p class="text-gray-600 mb-8">We will contact you shortly.</p>
        <a href="{{ route('home') }}"
           class="inline-block rounded-md bg-indigo-600 px-6 py-2.5 font-medium text-white hover:bg-indigo-700">
            Back to shop
        </a>
    </div>
@endsection
