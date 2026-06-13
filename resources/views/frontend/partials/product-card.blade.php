<article class="bg-white rounded-lg border border-gray-200 overflow-hidden flex flex-col">
    <a href="{{ $product->url() }}" class="block aspect-[4/3] bg-gray-100">
        @if ($product->photo)
            <img src="{{ asset('uploads/products/medium/'.$product->photo) }}"
                 alt="{{ $product->title }}" loading="lazy"
                 class="h-full w-full object-cover">
        @else
            <span class="flex h-full items-center justify-center text-gray-400 text-sm">No photo</span>
        @endif
    </a>
    <div class="p-4 flex flex-col gap-2 grow">
        @if ($product->brand)
            <a href="{{ $product->brand->url() }}" class="text-xs text-indigo-600 hover:underline">{{ $product->brand->title }}</a>
        @endif
        <h3 class="font-medium leading-snug">
            <a href="{{ $product->url() }}" class="hover:underline">{{ $product->title }}</a>
        </h3>
        @if ($product->fulldesc)
            <p class="text-sm text-gray-500 line-clamp-2">{!! strip_tags($product->fulldesc) !!}</p>
        @endif
        <div class="mt-auto flex items-center justify-between">
            <span class="text-lg font-semibold">${{ price($product->cost) }}</span>
            <form action="{{ route('cart.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="qty" value="1">
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700">
                    Add to cart
                </button>
            </form>
        </div>
    </div>
</article>
