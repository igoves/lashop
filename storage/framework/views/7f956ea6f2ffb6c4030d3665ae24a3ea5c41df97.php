<?php $__env->startSection('title', $pages->title); ?>
<?php $__env->startSection('meta_desc', $pages->meta_desc); ?>
<?php $__env->startSection('meta_key', $pages->meta_key); ?>

<?php $__env->startSection('content'); ?>
<?php echo e(Breadcrumbs::render('pages', $pages), false); ?>

<h1><?php echo e($pages->title, false); ?></h1>
<?php echo $pages->fulldesc; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.'.config('template').'.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>