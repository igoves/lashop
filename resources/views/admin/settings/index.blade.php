@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Settings</h1>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex border-b border-gray-200">
            @foreach($groups as $key => $group)
                <a href="{{ route('admin.settings.index', ['tab' => $key]) }}"
                   class="px-6 py-3 text-sm font-medium transition {{ $activeTab === $key ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ $group['label'] }}
                </a>
            @endforeach
        </div>

        @foreach($groups as $key => $group)
            @if($activeTab === $key)
                <form action="{{ route('admin.settings.bulkUpdate') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="p-6 space-y-4">
                        @foreach($group['settings'] as $slug => $setting)
                            <div>
                                <label for="settings[{{ $slug }}]" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $setting['title'] }}
                                    <span class="text-xs text-gray-400 font-mono">({{ $slug }})</span>
                                </label>
                                @if(in_array($slug, ['text_on_home', 'text_on_footer', 'order_statuses', 'delivery_methods', 'payment_methods']))
                                    <textarea id="settings[{{ $slug }}]" name="settings[{{ $slug }}]" rows="4"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('settings.'.$slug, $setting['value']) }}</textarea>
                                @else
                                    <input type="text" id="settings[{{ $slug }}]" name="settings[{{ $slug }}]"
                                           value="{{ old('settings.'.$slug, $setting['value']) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                            Save {{ $group['label'] }}
                        </button>
                    </div>
                </form>
            @endif
        @endforeach
    </div>
</div>
@endsection
