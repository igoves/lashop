<?php if($errors->hasBag('exception')): ?>
    <?php $error = $errors->getBag('exception');?>
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4>
            <i class="icon fa fa-warning"></i>
            <i style="border-bottom: 1px dotted #fff;cursor: pointer;" title="<?php echo e($error->get('type')[0], false); ?>" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;"><?php echo e(class_basename($error->get('type')[0]), false); ?></i>
            In <i title="<?php echo e($error->get('file')[0], false); ?> line <?php echo e($error->get('line')[0], false); ?>" style="border-bottom: 1px dotted #fff;cursor: pointer;" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;"><?php echo e(basename($error->get('file')[0]), false); ?> line <?php echo e($error->get('line')[0], false); ?></i> :
        </h4>
        <p><?php echo $error->get('message')[0]; ?></p>
    </div>
<?php endif; ?>