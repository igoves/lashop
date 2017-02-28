<div class="panel panel-body">
    <div class="row">
        <div class="col-md-1">
            {!! Form::open(['method'  => 'delete', 'route' => ['cart.destroy', $item['id']]]) !!}
            {!! Form::submit('x', ['class' => 'btn btn-danger btn-xs']) !!}
            {!! Form::close() !!}
        </div>
        <div class="col-md-2">
            <a href="/{{ $item['id'] }}-{{ $item['slug'] }}" title="{{ $item['name'] }}">
                {{ Html::image($item['image'], $item['name'], ['class'=>'img-responsive']) }}
            </a>
        </div>
        <div class="col-md-4">
            <b>{!! link_to_route('products.show', $item['name'], [$item['id'], $item['slug']]) !!}</b>
        </div>
        <div class="col-md-2">
            {{ $item['qty'] }}
        </div>
        <div class="col-md-3 text-center">
            {{ $item['cost'] }} $
        </div>
    </div>
</div>
<div class="clearfix"></div>