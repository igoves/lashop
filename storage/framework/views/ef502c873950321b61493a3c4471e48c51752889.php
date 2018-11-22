<div class="col-xs-3 col-md-4 col-md-4 col-sm-6">
    <div class="card" style="background: #fff;">
        <a href="/<?php echo e($product->id, false); ?>-<?php echo e($product->slug, false); ?>" title="<?php echo e($product->title, false); ?>">
            <?php if( !empty($product->photo) ): ?>
                <?php echo e(Html::image('/uploads/products/medium/'.str_replace('images/', '', str_replace('.jpg', '', $product->photo)), $product->title, ['class'=>'img-responsive', 'style'=>'width:100%;']), false); ?>

            <?php else: ?>
                <?php echo e(Html::image('https://dummyimage.com/762x428/000/fff.jpg&text=No+image', $product->title, ['class'=>'img-responsive', 'style'=>'width:100%;']), false); ?>

            <?php endif; ?>
        </a>
        <div class="card-body">
            <b>
                <?php echo link_to_route('products.show', $product->title, [$product->id, $product->slug]); ?>

            </b>
            <div class="text-right"><?php echo e($product->cost*config('rate'), false); ?> $</div>
        </div>
    </div>
</div>