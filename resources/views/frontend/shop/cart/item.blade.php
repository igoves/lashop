
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-1">
                {{ Form::open(['method'  => 'delete', 'route' => ['cart.destroy', $item['id']]]) }}
                {{ Form::button('x', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm']) }}
                {{ Form::close() }}
            </div>
            <div class="col-md-2">
                <a href="/{{ $item['id'] }}-{{ $item['slug'] }}" title="{{ $item['name'] }}">
                    @if ( $item['image'] !== null )
                        {{ Html::image('/uploads/'.$item['image'], $item['name'], ['class'=>'img-responsive', 'style'=>'width:100%;']) }}
                    @else
                        {{ Html::image('https://dummyimage.com/762x428/000/fff.jpg&text=No+image', $item['name'], ['class'=>'img-responsive', 'style'=>'width:100%;']) }}
                    @endif
                </a>
            </div>
            <div class="col-md-5">
                <b>{{ link_to_route('products.show', $item['name'], [$item['id'], $item['slug']]) }}</b>
            </div>
            <div class="col-md-2">
                {{ $item['qty'] }}
            </div>
            <div class="col-md-2 text-center">
                {{ $item['cost'] }} $
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>