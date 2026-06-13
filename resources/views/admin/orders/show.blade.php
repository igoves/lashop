@extends('admin.layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Order #{{ $order->id }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                &larr; Back to Orders
            </a>
            <a href="{{ route('admin.orders.edit', $order) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                  onsubmit="return confirm('Delete this order?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    @if($order->legacy_order !== null)
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h2 class="text-sm font-semibold text-yellow-800 uppercase mb-2">Archive (legacy order)</h2>
            <pre class="text-sm text-yellow-900 whitespace-pre-wrap">{{ $order->legacy_order }}</pre>
        </div>
    @endif

    @php
        $orderStatuses = array_filter(array_map('trim', explode("\n", setting('order_statuses', "New\nIn Progress\nDone"))));
    @endphp

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer Details</h2>
        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">Name</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">Email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->email }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">Phone</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->phone }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('Y-m-d H:i') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">Status</dt>
                <dd class="mt-1">
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="inline-flex">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()"
                                class="text-xs font-semibold rounded-full px-2 py-1 border-0 focus:ring-2 focus:ring-indigo-500 cursor-pointer
                                       @if($order->status === 'New') bg-blue-100 text-blue-800
                                       @elseif($order->status === 'In Progress') bg-yellow-100 text-yellow-800
                                       @elseif($order->status === 'Done') bg-green-100 text-green-800
                                       @else bg-gray-100 text-gray-800 @endif">
                            @foreach($orderStatuses as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </form>
                </dd>
            </div>
            @if($order->delivery_method)
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">Delivery</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->delivery_method }}</dd>
            </div>
            @endif
            @if($order->payment_method)
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">Payment</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->payment_method }}</dd>
            </div>
            @endif
            @if($order->comment)
            <div class="col-span-2">
                <dt class="text-xs font-medium text-gray-500 uppercase">Comment</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->comment }}</dd>
            </div>
            @endif
        </dl>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Order Items</h2>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($order->items as $item)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $item->title }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($item->price, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $item->qty }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($item->subtotal(), 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-sm text-gray-500 text-center">No items.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-lg shadow p-6 flex justify-end">
        <div class="text-right">
            <span class="text-lg font-semibold text-gray-800">Total: </span>
            <span class="text-xl font-bold text-gray-900">{{ number_format($order->total, 2) }}</span>
        </div>
    </div>
</div>
@endsection
