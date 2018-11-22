<?php if(Session::has('error')): ?>
    <?php $error = Session::get('error');?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i><?php echo e(array_get($error->get('title'), 0), false); ?></h4>
        <p><?php echo array_get($error->get('message'), 0); ?></p>
    </div>
<?php elseif(Session::has('errors')): ?>
    <?php $errors = Session::get('errors');?>
    <?php if($errors->hasBag('error')): ?>
      <div class="alert alert-danger alert-dismissable">

        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <?php $__currentLoopData = $errors->getBag("error")->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p><?php echo array_get($message, 0); ?></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
<?php endif; ?>