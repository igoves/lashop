<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Dashboard') — {{ setting('site_name', config('app.name')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex">

    @php
        $sidebarCounts = [
            'categories' => \App\Models\Shop\Category::count(),
            'brands' => \App\Models\Shop\Brand::count(),
            'products' => \App\Models\Shop\Product::count(),
            'orders' => \App\Models\Shop\Order::count(),
            'pages' => \App\Models\Page::count(),
            'news' => \App\Models\News::count(),
            'users' => \App\Models\User::where('is_admin', false)->count(),
        ];
    @endphp

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col h-screen sticky top-0 overflow-y-auto">
        <div class="px-6 py-5 text-xl font-bold border-b border-gray-700">
            {{ setting('site_name', config('app.name')) }}
        </div>
        <nav class="flex-1 px-4 py-6 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center justify-between gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
                    Dashboard
                </span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center justify-between gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                    Categories
                </span>
                <span class="text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded-full">{{ $sidebarCounts['categories'] }}</span>
            </a>
            <a href="{{ route('admin.brands.index') }}"
               class="flex items-center justify-between gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.brands.*') ? 'bg-gray-700' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3ZM9.568 3H13.5a2.25 2.25 0 0 1 2.25 2.25V9"/></svg>
                    Brands
                </span>
                <span class="text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded-full">{{ $sidebarCounts['brands'] }}</span>
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="flex items-center justify-between gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.products.*') ? 'bg-gray-700' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                    Products
                </span>
                <span class="text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded-full">{{ $sidebarCounts['products'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="flex items-center justify-between gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                    Orders
                </span>
                <span class="text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded-full">{{ $sidebarCounts['orders'] }}</span>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center justify-between gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                    Clients
                </span>
                <span class="text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded-full">{{ $sidebarCounts['users'] }}</span>
            </a>
            <a href="{{ route('admin.pages.index') }}"
               class="flex items-center justify-between gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.pages.*') ? 'bg-gray-700' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    Pages
                </span>
                <span class="text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded-full">{{ $sidebarCounts['pages'] }}</span>
            </a>
            <a href="{{ route('admin.news.index') }}"
               class="flex items-center justify-between gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.news.*') ? 'bg-gray-700' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6V7.5Z"/></svg>
                    News
                </span>
                <span class="text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded-full">{{ $sidebarCounts['news'] }}</span>
            </a>
            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center justify-between gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700' : '' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    Settings
                </span>
            </a>
        </nav>

        {{-- User info + logout --}}
        <div class="border-t border-gray-700 px-4 py-4 space-y-2">
            <a href="{{ route('admin.settings.profile') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                </svg>
                <span class="text-sm text-gray-300">{{ auth()->user()->name }}</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" class="px-3">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 w-full px-3 py-2 rounded-md text-sm font-medium text-gray-400 hover:text-red-400 hover:bg-gray-700 transition"
                        title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main area --}}
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
        <main class="flex-1 p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>
