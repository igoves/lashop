<div class="input-group">
    <div class="input-group-addon">
        <i class="fa fa-<?php echo e($icon, false); ?>"></i>
    </div>
    <input type="<?php echo e($type, false); ?>" class="form-control <?php echo e($id, false); ?>" placeholder="<?php echo e($placeholder, false); ?>" name="<?php echo e($name, false); ?>" value="<?php echo e(request($name, $value), false); ?>">
</div>