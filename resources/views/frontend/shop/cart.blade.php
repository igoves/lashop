@extends('frontend.layouts.app')

@section('title', 'Cart — '.setting('site_name', config('app.name')))

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Cart</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    @if ($items->isEmpty())
        <p class="text-gray-500">Your cart is empty. <a href="{{ route('home') }}" class="text-indigo-600 hover:underline">Go shopping</a>.</p>
    @else
        <div class="grid gap-8 lg:grid-cols-5">
            {{-- Checkout form — left --}}
            <div class="lg:col-span-2 order-2 lg:order-1">
                <h2 class="text-xl font-semibold mb-3">Checkout</h2>
                <form action="{{ route('orders.store') }}" method="POST"
                      class="bg-white rounded-lg border border-gray-200 p-4 space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium mb-1">Name *</label>
                        <input id="name" name="name" value="{{ old('name') }}" required
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium mb-1">Phone *</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}" required
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                        @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="comment" class="block text-sm font-medium mb-1">Comment</label>
                        <textarea id="comment" name="comment" rows="3"
                                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">{{ old('comment') }}</textarea>
                        @error('comment')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    @php
                        $deliveryMethods = array_filter(array_map('trim', explode("\n", setting('delivery_methods', ''))));
                        $paymentMethods = array_filter(array_map('trim', explode("\n", setting('payment_methods', ''))));
                    @endphp

                    @if(count($deliveryMethods) > 0)
                    <div>
                        <label class="block text-sm font-medium mb-2">Delivery Method</label>
                        <div class="space-y-2">
                            @foreach($deliveryMethods as $method)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="delivery_method" value="{{ $method }}"
                                           {{ old('delivery_method') === $method ? 'checked' : '' }}
                                           class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm">{{ $method }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('delivery_method')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    @endif

                    @if(count($paymentMethods) > 0)
                    <div>
                        <label class="block text-sm font-medium mb-2">Payment Method</label>
                        <div class="space-y-2">
                            @foreach($paymentMethods as $method)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="payment_method" value="{{ $method }}"
                                           {{ old('payment_method') === $method ? 'checked' : '' }}
                                           class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm">{{ $method }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    @endif
                    <div>
                        <button type="submit"
                                class="w-full rounded-md bg-indigo-600 px-6 py-2.5 font-medium text-white hover:bg-indigo-700">
                            Place order
                        </button>
                    </div>
                </form>
            </div>

            {{-- Cart items — right --}}
            <div class="lg:col-span-3 order-1 lg:order-2">
                <div class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100">
                    @foreach ($items as $item)
                        <div class="flex items-center gap-4 p-4">
                            <a href="{{ $item['product']->url() }}" class="block w-16 h-16 bg-gray-100 rounded shrink-0 overflow-hidden">
                                @if ($item['product']->photo)
                                    <img src="{{ asset(config('shop.image_path').'/small/'.$item['product']->photo) }}"
                                         alt="{{ $item['product']->title }}" class="h-full w-full object-cover">
                                @endif
                            </a>
                            <div class="grow min-w-0">
                                <a href="{{ $item['product']->url() }}" class="font-medium hover:underline truncate block">
                                    {{ $item['product']->title }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    ${{ price($item['product']->cost) }} × {{ $item['qty'] }}
                                </p>
                            </div>
                            <span class="font-semibold shrink-0">${{ number_format($item['sum'], 2, '.', ' ') }}</span>
                            <form action="{{ route('cart.destroy', $item['product']->id) }}" method="POST" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Remove {{ $item['product']->title }}"
                                        class="text-gray-400 hover:text-red-600 px-2 text-xl leading-none">&times;</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between mt-4">
                    <span class="text-lg">Total:</span>
                    <span class="text-2xl font-bold" data-cart-total>${{ number_format($total, 2, '.', ' ') }}</span>
                </div>
            </div>
        </div>
    @endif
@endsection
