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
    <link rel="stylesheet" href="/vendor/laravel-admin/nprogress/nprogress.css">
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>

@include('frontend.partials.nav')

<section class="container">
    <br/><br/><br/>
    <div id="pjax-container">
    @yield('content')
    </div>
</section>

<style>
.context-dark {
    color: rgba(255, 255, 255, 0.8);
}
.footer-classic a, .footer-classic a:focus, .footer-classic a:active {
    color: #ffffff;
}
</style>
<footer class=" footer-classic context-dark " style="background: #2d3246; padding-top:15px; margin-top:25px;">
    <div class="container">
        <div class="row row-30">
            <div class="col-md-4 col-xl-5">
                <div class="pr-xl-4">
                    <p>{{ config('text_on_footer') }}</p>
                    <p class="rights">©  {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
                </div>
            </div>
            <div class="col-md-4">
                <h5>Contacts</h5>
                <dl class="contact-list">
                    <dt>Address:</dt>
                    <dd>{{ config('address')  }}</dd>
                </dl>
                <dl class="contact-list">
                    <dt>email:</dt>
                    <dd><a href="mailto:{{ config('email') }}">{{ config('email') }}</a></dd>
                </dl>
                <dl class="contact-list">
                    <dt>phones:</dt>
                    <dd><a href="tel:{{ config('tel_1') }}">{{ config('tel_1') }}</a> or <a href="tel:{{ config('tel_2') }}">{{ config('tel_2') }}</a>
                    </dd>
                </dl>
            </div>
            <div class="col-md-4 col-xl-3">
                <h5>Links</h5>
                <ul class="nav-list list-unstyled">
                    <li><a href="/about.html">About</a></li>
                    <li><a href="/contacts.html">Contacts</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
<script src="/vendor/laravel-admin/nprogress/nprogress.js"></script>
<script src="{{ asset('js/pjax.js') }}"></script>
<script src="{{ asset('js/core.js?6') }}"></script>
</body>
</html>