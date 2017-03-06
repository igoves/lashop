<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'LaShop') }}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
             {!! $menu->asUl(array('class' => 'nav navbar-nav')) !!}
               <div class="navbar-form navbar-right">

                   <a href="/cart" class="btn btn-success" style="margin-right:15px">
                       Cart ({{ $cart_qty }})
                   </a>

                    {!! Form::open(['route' => 'search', 'method' => 'post', 'class' => 'pull-right']) !!}
                     <div class="input-group">
                        {!! Form::text('Search', null, ['class' => 'form-control', 'placeholder' => 'Search', 'name' => 'story' ]) !!}
                        <span class="input-group-btn">
                            {!! Form::button('GO', ['class' => 'btn btn-default', 'type' => 'submit']) !!}
                        </span>
                     </div>
                    {!! Form::close() !!}

              </div>
        </div><!--/.navbar-collapse -->
    </div>
</nav>