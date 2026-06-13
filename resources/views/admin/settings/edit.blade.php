@extends('admin.layouts.app')

@section('title', 'Edit Setting')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Setting: {{ $setting->slug }}</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.settings.update', $setting) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <p class="text-sm font-mono text-gray-600">{{ $setting->slug }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <p class="text-sm text-gray-600">{{ $setting->title }}</p>
            </div>

            <div class="mb-6">
                <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                <textarea id="value" name="value" rows="4"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('value') border-red-500 @enderror">{{ old('value', $setting->value) }}</textarea>
                @error('value')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    Save
                </button>
                <a href="{{ route('admin.settings.index') }}"
                   class="text-sm text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
