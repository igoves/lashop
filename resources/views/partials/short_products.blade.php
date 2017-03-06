<div class="col-md-4">
    <div class="thumbnail" style="background: #fff;">
        <a href="/{{ $product->id }}-{{ $product->slug }}" title="{{ $product->name }}">
            @if ( !empty($product->image) )
            {{ Html::image($product->image, $product->name, ['class'=>'img-responsive']) }}
            @else
             {{ Html::image('https://dummyimage.com/640x480/000/fff.jpg&text=No+image', $product->name, ['class'=>'img-responsive']) }}
            @endif
        </a>
        <div class="caption">
            <b>
                {!! link_to_route('products.show', $product->name, [$product->id, $product->slug]) !!}
            </b>
            <div class="pull-right">{{ $product->cost }} $</div>
        </div>
    </div>
</div>
