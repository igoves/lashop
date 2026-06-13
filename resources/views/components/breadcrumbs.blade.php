@if (count($items))
    <nav aria-label="Breadcrumb" class="mb-4 text-sm text-gray-500">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="{{ route('home') }}" class="hover:text-gray-900 hover:underline">Home</a></li>
            @foreach ($items as $item)
                <li aria-hidden="true">/</li>
                <li>
                    @if (! $loop->last && ! empty($item['url']))
                        <a href="{{ $item['url'] }}" class="hover:text-gray-900 hover:underline">{{ $item['title'] }}</a>
                    @else
                        <span aria-current="page" class="text-gray-900">{{ $item['title'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
