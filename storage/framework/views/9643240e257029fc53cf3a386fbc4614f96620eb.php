<?php $__env->startSection('title', $product->title); ?>
<?php $__env->startSection('meta_desc', $product->meta_desc); ?>
<?php $__env->startSection('meta_key', $product->meta_key); ?>

<?php $__env->startSection('content'); ?>
<?php echo e(Breadcrumbs::render('product_full', $product), false); ?>


<div class="row">
    <div class="col">
        <?php if( !empty($product->photo) ): ?>
            <?php echo e(Html::image('/uploads/products/big/'.str_replace('images/', '', str_replace('.jpg', '', $product->photo)), $product->title, ['class'=>'img-responsive', 'style'=>'max-width:100%;']), false); ?>

        <?php else: ?>
            <?php echo e(Html::image('https://dummyimage.com/640x480/000/fff.jpg&text=No+image', $product->title, ['class'=>'img-responsive']), false); ?>

        <?php endif; ?>
    </div>
    <div class="col">
        <h2><?php echo e($product->title, false); ?></h2>
        <small class="text-muted">Category: <?php echo e($product->categories['title'], false); ?></small>
        <div><br/>Description<br/><?php echo $product->fulldesc; ?></div>
        <hr/>

        <?php echo e(Form::open(['route' => 'cart.store']), false); ?>

        <div class="row">
            <div class="col-md-3">
                <b>COST:</b> <?php echo e($product->cost*config('rate'), false); ?> $
            </div>
            <div class="col-md-3">
                <?php echo e(Form::text('qty', 1, ['class' => 'form-control text-center']), false); ?>

            </div>
            <div class="col-md-6">
                <?php echo e(Form::submit('Buy', ['class' => 'btn btn-primary btn-block']), false); ?>

            </div>
        </div>
        <?php echo e(Form::hidden('product_id', $product->id), false); ?>

        <?php echo e(Form::close(), false); ?>


        <hr/>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.'.config('template').'.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>