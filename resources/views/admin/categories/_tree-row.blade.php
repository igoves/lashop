<tr>
    <td class="px-6 py-4">
        @if($category->logo)
            <img src="{{ asset('uploads/categories/' . $category->logo) }}" alt="{{ $category->title }}"
                 class="w-12 h-9 object-cover rounded">
        @else
            <span class="text-gray-400 text-xs">No logo</span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
        style="padding-left: {{ 24 + $depth * 24 }}px">
        {{ $category->title }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">{{ $category->slug }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center gap-3">
        <a href="{{ $category->url() }}" target="_blank"
           class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-md transition"
           title="View on frontend">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
            </svg>
        </a>
        <a href="{{ route('admin.categories.edit', $category) }}"
           class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 hover:bg-indigo-50 rounded-md transition"
           title="Edit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
            </svg>
        </a>
        @php $hasChildren = $category->children->isNotEmpty(); @endphp
        @php $hasProducts = ($productCounts[$category->id] ?? 0) > 0; @endphp
        @if($hasChildren || $hasProducts)
            <span class="text-xs text-gray-400 italic">
                {{ $hasChildren ? 'has children' : 'has products' }}
            </span>
        @else
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                  onsubmit="return confirm('Delete this category?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center justify-center w-8 h-8 text-red-500 hover:bg-red-50 rounded-md transition"
                        title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                </button>
            </form>
        @endif
    </td>
</tr>
@foreach($category->children as $child)
    @include('admin.categories._tree-row', ['category' => $child, 'depth' => $depth + 1, 'productCounts' => $productCounts])
@endforeach
