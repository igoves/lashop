
<nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background: #2d3246;">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(url('/'), false); ?>"><?php echo e(config('app.name', 'Lashop'), false); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <?php echo $top_menu->asUl(array('class' => 'navbar-nav mr-auto')); ?>

            <a href="/cart" class="btn btn-success" style="margin-right:15px">
                Cart (<?php echo e($cart_qty, false); ?>)
            </a>
            <?php echo e(Form::open(['route' => 'search', 'method' => 'post', 'class' => 'form-inline my-2 my-lg-0']), false); ?>

            <div class="input-group">
                <?php echo e(Form::text('Search', old('story'), ['class' => 'form-control', 'placeholder' => 'Search', 'name' => 'story' ]), false); ?>             <div class="input-group-append">
                    <?php echo e(Form::button('GO', ['class' => 'btn btn-default my-2 my-sm-0', 'type' => 'submit']), false); ?>

                </div>
            </div>
            <?php echo e(Form::close(), false); ?>


        </div>
    </div>
</nav>