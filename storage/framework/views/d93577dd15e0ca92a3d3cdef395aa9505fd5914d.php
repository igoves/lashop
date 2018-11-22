<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale'), false); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo e(Admin::title(), false); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css"), false); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/font-awesome/css/font-awesome.min.css"), false); ?>">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/AdminLTE/dist/css/skins/" . config('admin.skin') .".min.css"), false); ?>">

    <?php echo Admin::css(); ?>

    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/laravel-admin/laravel-admin.css"), false); ?>">
    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/nprogress/nprogress.css"), false); ?>">
    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/sweetalert/dist/sweetalert.css"), false); ?>">
    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/nestable/nestable.css"), false); ?>">
    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/toastr/build/toastr.min.css"), false); ?>">
    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/bootstrap3-editable/css/bootstrap-editable.css"), false); ?>">
    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/google-fonts/fonts.css"), false); ?>">
    <link rel="stylesheet" href="<?php echo e(admin_asset("/vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css"), false); ?>">

    <!-- REQUIRED JS SCRIPTS -->
    <script src="<?php echo e(admin_asset ("/vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"), false); ?>"></script>
    <script src="<?php echo e(admin_asset ("/vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js"), false); ?>"></script>
    <script src="<?php echo e(admin_asset ("/vendor/laravel-admin/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js"), false); ?>"></script>
    <script src="<?php echo e(admin_asset ("/vendor/laravel-admin/AdminLTE/dist/js/app.min.js"), false); ?>"></script>
    <script src="<?php echo e(admin_asset ("/vendor/laravel-admin/jquery-pjax/jquery.pjax.js"), false); ?>"></script>
    <script src="<?php echo e(admin_asset ("/vendor/laravel-admin/nprogress/nprogress.js"), false); ?>"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="hold-transition <?php echo e(config('admin.skin'), false); ?> <?php echo e(join(' ', config('admin.layout')), false); ?>">
<div class="wrapper">

    <?php echo $__env->make('admin::partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php echo $__env->make('admin::partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div class="content-wrapper" id="pjax-container">
        <?php echo $__env->yieldContent('content'); ?>
        <?php echo Admin::script(); ?>

    </div>

    <?php echo $__env->make('admin::partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

</div>

<!-- ./wrapper -->

<script>
    function LA() {}
    LA.token = "<?php echo e(csrf_token(), false); ?>";
</script>

<!-- REQUIRED JS SCRIPTS -->
<script src="<?php echo e(admin_asset ("/vendor/laravel-admin/nestable/jquery.nestable.js"), false); ?>"></script>
<script src="<?php echo e(admin_asset ("/vendor/laravel-admin/toastr/build/toastr.min.js"), false); ?>"></script>
<script src="<?php echo e(admin_asset ("/vendor/laravel-admin/bootstrap3-editable/js/bootstrap-editable.min.js"), false); ?>"></script>
<script src="<?php echo e(admin_asset ("/vendor/laravel-admin/sweetalert/dist/sweetalert.min.js"), false); ?>"></script>
<?php echo Admin::js(); ?>

<script src="<?php echo e(admin_asset ("/vendor/laravel-admin/laravel-admin/laravel-admin.js"), false); ?>"></script>

</body>
</html>
