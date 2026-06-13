@extends('admin.layouts.app')

@section('title', 'New Order')

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">New Order</h1>
        <a href="{{ route('admin.orders.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition">
            &larr; Back to Orders
        </a>
    </div>

    <form action="{{ route('admin.orders.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                    <input id="comment" name="comment" type="text" value="{{ old('comment') }}"
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
            </div>
            @error('lines')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                Create Order
            </button>
            <a href="{{ route('admin.orders.index') }}"
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
