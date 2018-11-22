<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filter-modal"><i class="fa fa-filter"></i>&nbsp;&nbsp;<?php echo e(trans('admin.filter'), false); ?></a>
    <a href="<?php echo $action; ?>" class="btn btn-sm btn-facebook"><i class="fa fa-undo"></i>&nbsp;&nbsp;<?php echo e(trans('admin.reset'), false); ?></a>
</div>

<div class="modal fade" id="filter-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo e(trans('admin.filter'), false); ?></h4>
            </div>
            <form action="<?php echo $action; ?>" method="get" pjax-container>
                <div class="modal-body">
                    <div class="form">
                        <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group">
                                <?php echo $filter->render(); ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit"><?php echo e(trans('admin.submit'), false); ?></button>
                    <button type="reset" class="btn btn-warning pull-left"><?php echo e(trans('admin.reset'), false); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>