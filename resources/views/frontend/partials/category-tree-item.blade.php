<li>
    <a href="{{ $item->url() }}"
       @class([
           'block px-3 py-2 hover:bg-gray-50',
           'font-semibold text-indigo-700' => $current?->id === $item->id,
       ])
       style="padding-left: {{ 12 + $depth * 14 }}px">
        {{ $item->title }}
        @if ($item->products_count ?? false)
            <span class="text-gray-400 text-xs ml-1">({{ $item->products_count }})</span>
        @endif
    </a>
    @if (in_array($item->id, $ancestorIds) && $item->children->isNotEmpty())
        <ul>
            @foreach ($item->children as $child)
                @include('frontend.partials.category-tree-item', ['item' => $child, 'depth' => $depth + 1, 'current' => $current, 'ancestorIds' => $ancestorIds])
            @endforeach
        </ul>
    @endif
</li>
