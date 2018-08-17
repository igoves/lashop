
<nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background: #2d3246;">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Lashop') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            {!! $top_menu->asUl(array('class' => 'navbar-nav mr-auto')) !!}
            <a href="/cart" class="btn btn-outline-success" style="margin-right:15px">
                Cart ({{ $cart_qty }})
            </a>
            {{ Form::open(['route' => 'search', 'method' => 'post', 'class' => 'form-inline my-2 my-lg-0']) }}
            <div class="input-group">
                {{ Form::text('Search', old('story'), ['class' => 'form-control', 'placeholder' => 'Search', 'name' => 'story' ]) }}             <div class="input-group-append">
                    {{ Form::button('GO', ['class' => 'btn btn-default my-2 my-sm-0', 'type' => 'submit']) }}
                </div>
            </div>
            {{ Form::close() }}

        </div>
    </div>
</nav>