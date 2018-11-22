<div class="form-group">
    <label><?php echo e($label, false); ?></label>
    <?php echo $__env->make($presenter->view(), \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>