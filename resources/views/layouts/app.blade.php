<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_desc', 'Shop based on Laravel')">
    <meta name="keywords" content="@yield('meta_key', 'shop, laravel, e-commerce')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>

    @include('partials.nav')

    <section class="container">
        <div class="row">
            <br/><br/><br/>
            @yield('content')
        </div>
    </section>

    {{--<script src="{{ asset('js/app.js') }}"></script>--}}

    <footer class="text-center">
        <hr/>
        {{ date("Y") }} &copy; {{ config('app.name') }}
        <br/><br/>
    </footer>

</body>
</html>
