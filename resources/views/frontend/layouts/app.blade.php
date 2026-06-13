@if (request()->header('X-Fullajax'))
    {{-- fullajax: title and content only, no layout wrapper --}}
    <title>@yield('title', setting('site_name', config('app.name')))</title>
    @yield('content')
@else
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', setting('site_name', config('app.name')))</title>
    <meta name="description" content="@yield('meta_description', setting('text_on_footer', 'Shop based on Laravel'))">
    <meta name="keywords" content="@yield('meta_keywords', 'shop, laravel, e-commerce')">
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @endif
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ setting('site_name', config('app.name')) }}">
    <link rel="apple-touch-icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-gray-50 text-gray-900 antialiased">

<header class="bg-white border-b border-gray-200 sticky top-0 z-20">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center gap-4">
        <a href="{{ route('home') }}" class="text-xl font-semibold tracking-tight shrink-0">
            {{ setting('site_name', config('app.name')) }}
        </a>

        <nav class="hidden md:block grow" aria-label="Main">
            <x-menu />
        </nav>

        <form action="{{ route('search') }}" method="POST" class="hidden sm:flex items-center gap-1">
            @csrf
            <label for="search-input" class="sr-only">Search</label>
            <input id="search-input" type="search" name="story" value="{{ $phrase ?? '' }}"
                   placeholder="Search…" required
                   class="w-36 lg:w-48 rounded-md border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="rounded-md px-2 py-1.5 text-sm text-gray-600 hover:text-gray-900">🔍</button>
        </form>

        <a href="{{ route('cart.index') }}" data-no-ajax
           class="relative shrink-0 inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.046A1.125 1.125 0 0 0 18.056 3H5.105m2.394 11.25-1.5-6h13.5"/>
            </svg>
            <span class="inline-flex items-center justify-center rounded-full bg-white/20 px-1.5 text-xs"
                   data-cart-count>{{ app(App\Services\CartService::class)->count() }}</span>
        </a>

        <div class="relative shrink-0" data-dropdown>
            <button type="button" data-dropdown-toggle
                    class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
                    aria-label="Account menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                </svg>
            </button>
            <div data-dropdown-menu
                 class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                @auth
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('cart.index') }}" data-no-ajax class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cart</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Login</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Register</a>
                @endauth
            </div>
        </div>

        <button type="button" class="md:hidden p-2 -mr-2" data-menu-toggle aria-label="Menu" aria-expanded="false">
            <span class="block w-5 border-t-2 border-gray-700 mb-1"></span>
            <span class="block w-5 border-t-2 border-gray-700 mb-1"></span>
            <span class="block w-5 border-t-2 border-gray-700"></span>
        </button>
    </div>

    <nav class="md:hidden hidden border-t border-gray-100 px-4 py-2" data-mobile-menu aria-label="Mobile">
        <x-menu mobile />
    </nav>
</header>

<main id="content" class="grow max-w-6xl mx-auto w-full px-4 py-6">
    @yield('content')
</main>

<footer class="bg-slate-800 text-slate-200 mt-8">
    <div class="max-w-6xl mx-auto px-4 py-8 grid gap-8 md:grid-cols-4 text-sm">
        <div>
            <p>{{ setting('text_on_footer') }}</p>
            <p class="mt-3 text-slate-400">© {{ date('Y') }} {{ setting('site_name', config('app.name')) }}. All Rights Reserved.</p>
        </div>
        <div>
            <h5 class="font-semibold text-white mb-2">Categories</h5>
            <ul class="space-y-1">
                @foreach (\App\Models\Shop\Category::tree() as $category)
                    <li><a class="hover:underline" href="{{ $category->url() }}">{{ $category->title }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h5 class="font-semibold text-white mb-2">Contacts</h5>
            <p>{{ setting('address') }}</p>
            @if (setting('email'))
                <p><a class="hover:underline" href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></p>
            @endif
            <p>
                @if (setting('tel_1'))<a class="hover:underline" href="tel:{{ setting('tel_1') }}">{{ setting('tel_1') }}</a>@endif
                @if (setting('tel_2')) or <a class="hover:underline" href="tel:{{ setting('tel_2') }}">{{ setting('tel_2') }}</a>@endif
            </p>
        </div>
        <div>
            <h5 class="font-semibold text-white mb-2">Links</h5>
            <ul class="space-y-1">
                @foreach ($footerPages as $page)
                    <li><a class="hover:underline" href="{{ $page->url() }}">{{ $page->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>

</body>
</html>
@endif
