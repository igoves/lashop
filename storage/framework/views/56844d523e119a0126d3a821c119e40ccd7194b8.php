<?php if(Admin::user()->visible($item['roles'])): ?>
    <?php if(!isset($item['children'])): ?>
        <li>
            <?php if(url()->isValidUrl($item['uri'])): ?>
                <a href="<?php echo e($item['uri'], false); ?>" target="_blank">
            <?php else: ?>
                 <a href="<?php echo e(admin_base_path($item['uri']), false); ?>">
            <?php endif; ?>
                <i class="fa <?php echo e($item['icon'], false); ?>"></i>
                <span><?php echo e($item['title'], false); ?></span>
            </a>
        </li>
    <?php else: ?>
        <li class="treeview">
            <a href="#">
                <i class="fa <?php echo e($item['icon'], false); ?>"></i>
                <span><?php echo e($item['title'], false); ?></span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php $__currentLoopData = $item['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('admin::partials.menu', $item, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </li>
    <?php endif; ?>
<?php endif; ?>