<div class="col-xs-3 col-md-4 col-md-4 col-sm-6">
    <div class="card" style="background: #fff;">
        <a href="/{{ $product->id }}-{{ $product->slug }}" title="{{ $product->title }}">
            @if ( !empty($product->photo) )
                {{ Html::image('/uploads/'.$product->photo, $product->title, ['class'=>'img-responsive', 'style'=>'width:100%;']) }}
            @else
                {{ Html::image('https://dummyimage.com/762x428/000/fff.jpg&text=No+image', $product->title, ['class'=>'img-responsive', 'style'=>'width:100%;']) }}
            @endif
        </a>
        <div class="card-body">
            <b>
                {!! link_to_route('products.show', $product->title, [$product->id, $product->slug]) !!}
            </b>
            <div class="text-right">{{ $product->cost }} $</div>
        </div>
    </div>
</div>