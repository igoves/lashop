<ul {{ $attributes->class($mobile ? 'flex flex-col gap-1 py-1' : 'flex items-center gap-1') }}>
    @foreach ($categories as $category)
        <li class="relative group">
            <a href="{{ $category->url() }}"
               @class([
                   'flex items-center gap-1 rounded-md px-3 py-1.5 text-sm hover:bg-gray-100',
                   'font-semibold text-indigo-700' => request()->path() === $category->fullPath(),
               ])>
                {{ $category->title }}
                @if ($category->children->isNotEmpty())
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                @endif
            </a>
            @if ($category->children->isNotEmpty())
                <ul class="hidden group-hover:block absolute left-0 top-full z-30 min-w-[200px] rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                    @foreach ($category->children as $child)
                        <li>
                            <a href="{{ $child->url() }}"
                               @class([
                                   'block px-4 py-1.5 text-sm hover:bg-gray-100',
                                   'font-semibold text-indigo-700' => request()->path() === $child->fullPath(),
                               ])>
                                {{ $child->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
    @foreach ($pages as $page)
        <li>
            <a href="{{ $page->url() }}"
               @class([
                   'block rounded-md px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100',
                   'font-semibold text-indigo-700' => request()->path() === $page->slug.'.html',
               ])>
                {{ $page->title }}
            </a>
        </li>
    @endforeach
</ul>
