<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_desc', 'Shop based on Laravel')">
    <meta name="keywords" content="@yield('meta_key', 'shop, laravel, e-commerce')">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
</head>
<body>


@include('partials.nav')

<section class="container">
    <div class="row">
        <br/><br/><br/>
        @yield('content')
    </div>
</section>

<footer class="text-center">
    <hr/>
    {{ date("Y") }} &copy; {{ config('app.name') }}
    <br/><br/>
</footer>

{{--<script type="text/javascript" src='/js/app.js'></script>--}}
</body>
</html>