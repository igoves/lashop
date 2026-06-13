@extends('admin.layouts.app')

@section('title', 'Edit Order #' . $order->id)

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Order #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.show', $order) }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition">
            &larr; Back to Order
        </a>
    </div>

    @php
        $orderStatuses = array_filter(array_map('trim', explode("\n", setting('order_statuses', "New\nIn Progress\nDone"))));
        $deliveryMethods = array_filter(array_map('trim', explode("\n", setting('delivery_methods', ''))));
        $paymentMethods = array_filter(array_map('trim', explode("\n", setting('payment_methods', ''))));
    @endphp

    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $order->name) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $order->phone) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $order->email) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select id="status" name="status" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base">
                        @foreach($orderStatuses as $status)
                            <option value="{{ $status }}" {{ old('status', $order->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                @if(count($deliveryMethods) > 0)
                <div>
                    <label for="delivery_method" class="block text-sm font-medium text-gray-700 mb-1">Delivery Method</label>
                    <select id="delivery_method" name="delivery_method"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base">
                        <option value="">— None —</option>
                        @foreach($deliveryMethods as $method)
                            <option value="{{ $method }}" {{ old('delivery_method', $order->delivery_method) === $method ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if(count($paymentMethods) > 0)
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select id="payment_method" name="payment_method"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base">
                        <option value="">— None —</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method }}" {{ old('payment_method', $order->payment_method) === $method ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="md:col-span-2">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                    <input id="comment" name="comment" type="text" value="{{ old('comment', $order->comment) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base @error('comment') border-red-500 @enderror">
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Order Items</h2>
                <button type="button" id="add-line"
                        class="inline-flex items-center justify-center w-8 h-8 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                        title="Add line">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </button>
            </div>

            <div id="lines" class="space-y-3">
                @forelse($order->items as $idx => $item)
                    <div class="flex items-center gap-3 line">
                        <select name="lines[{{ $idx }}][product_id]" required
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base">
                            <option value="">Select product…</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-cost="{{ $product->cost }}"
                                        {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->title }} ({{ number_format($product->cost, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="lines[{{ $idx }}][qty]" value="{{ $item->qty }}" min="1" required
                               class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <button type="button" class="remove-line inline-flex items-center justify-center w-8 h-8 text-red-500 hover:bg-red-50 rounded-md transition" title="Remove">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="flex items-center gap-3 line">
                        <select name="lines[0][product_id]" required
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base">
                            <option value="">Select product…</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-cost="{{ $product->cost }}">
                                    {{ $product->title }} ({{ number_format($product->cost, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="lines[0][qty]" value="1" min="1" required
                               class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <button type="button" class="remove-line inline-flex items-center justify-center w-8 h-8 text-red-500 hover:bg-red-50 rounded-md transition" title="Remove">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endforelse
            </div>
            @error('lines')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('lines.*.product_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('lines.*.qty')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                Save Changes
            </button>
            <a href="{{ route('admin.orders.show', $order) }}"
               class="text-sm text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>

<script>
document.getElementById('add-line').addEventListener('click', function() {
    const lines = document.getElementById('lines');
    const index = lines.querySelectorAll('.line').length;
    const first = lines.querySelector('.line');
    const clone = first.cloneNode(true);
    clone.querySelectorAll('select, input').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, '[' + index + ']');
        if (el.tagName === 'SELECT') el.value = '';
        if (el.type === 'number') el.value = '1';
    });
    lines.appendChild(clone);
});

document.getElementById('lines').addEventListener('click', function(e) {
    const btn = e.target.closest('.remove-line');
    if (btn && this.querySelectorAll('.line').length > 1) {
        btn.closest('.line').remove();
    }
});
</script>
@endsection
