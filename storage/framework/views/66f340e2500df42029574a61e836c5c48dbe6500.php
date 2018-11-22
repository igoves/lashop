
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-1">
                <?php echo e(Form::open(['method'  => 'delete', 'route' => ['cart.destroy', $item['id']]]), false); ?>

                <?php echo e(Form::button('x', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm']), false); ?>

                <?php echo e(Form::close(), false); ?>

            </div>
            <div class="col-md-2">
                <a href="/<?php echo e($item['id'], false); ?>-<?php echo e($item['slug'], false); ?>" title="<?php echo e($item['name'], false); ?>">
                    <?php if( $item['image'] !== null ): ?>
                        <?php echo e(Html::image('/uploads/products/small/'.str_replace('images/', '', str_replace('.jpg', '', $item['image'])), $item['name'], ['class'=>'img-responsive', 'style'=>'width:100%;']), false); ?>

                    <?php else: ?>
                        <?php echo e(Html::image('https://dummyimage.com/762x428/000/fff.jpg&text=No+image', $item['name'], ['class'=>'img-responsive', 'style'=>'width:100%;']), false); ?>

                    <?php endif; ?>
                </a>
            </div>
            <div class="col-md-5">
                <b><?php echo e(link_to_route('products.show', $item['name'], [$item['id'], $item['slug']]), false); ?></b>
            </div>
            <div class="col-md-2">
                <?php echo e($item['qty'], false); ?>

            </div>
            <div class="col-md-2 text-center">
                <?php echo e($item['cost']*config('rate'), false); ?> $
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>