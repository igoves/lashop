<div class="col-md-4">
    <div class="thumbnail" style="background: #fff;">
        <a href="/{{ $product->id }}-{{ $product->slug }}" title="{{ $product->name }}">
            {{ Html::image($product->image, $product->name, ['class'=>'img-responsive']) }}
        </a>
        <div class="caption">
            <b>
                {!! link_to_route('products.show', $product->name, [$product->id, $product->slug]) !!}
            </b>
            <div class="pull-right">{{ $product->cost }} $</div>
        </div>
    </div>
</div>