<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale'), false); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_desc', 'Shop based on Laravel'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_key', 'shop, laravel, e-commerce'); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token(), false); ?>">
    <title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
    <link href="<?php echo e(asset('css/app.css'), false); ?>" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/laravel-admin/nprogress/nprogress.css">
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
    </script>
</head>
<body>

<?php echo $__env->make('frontend.default.partials.nav', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<section class="container">
    <br/><br/><br/>
    <div id="pjax-container">
    <?php echo $__env->yieldContent('content'); ?>
    </div>
</section>

<style>
.context-dark {
    color: rgba(255, 255, 255, 0.8);
}
.footer-classic a, .footer-classic a:focus, .footer-classic a:active {
    color: #ffffff;
}
</style>
<footer class=" footer-classic context-dark " style="background: #2d3246; padding-top:15px; margin-top:25px;">
    <div class="container">
        <div class="row row-30">
            <div class="col-md-4 col-xl-5">
                <div class="pr-xl-4">
                    <p><?php echo e(config('text_on_footer'), false); ?></p>
                    <p class="rights">©  <?php echo e(date('Y'), false); ?> <?php echo e(config('app.name'), false); ?>. All Rights Reserved.</p>
                </div>
            </div>
            <div class="col-md-4">
                <h5>Contacts</h5>
                <dl class="contact-list">
                    <dt>Address:</dt>
                    <dd><?php echo e(config('address'), false); ?></dd>
                </dl>
                <dl class="contact-list">
                    <dt>email:</dt>
                    <dd><a href="mailto:<?php echo e(config('email'), false); ?>"><?php echo e(config('email'), false); ?></a></dd>
                </dl>
                <dl class="contact-list">
                    <dt>phones:</dt>
                    <dd><a href="tel:<?php echo e(config('tel_1'), false); ?>"><?php echo e(config('tel_1'), false); ?></a> or <a href="tel:<?php echo e(config('tel_2'), false); ?>"><?php echo e(config('tel_2'), false); ?></a>
                    </dd>
                </dl>
            </div>
            <div class="col-md-4 col-xl-3">
                <h5>Links</h5>
                <ul class="nav-list list-unstyled">
                    <li><a href="/about.html">About</a></li>
                    <li><a href="/contacts.html">Contacts</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<script src="<?php echo e(asset('js/app.js'), false); ?>"></script>
<script src="/vendor/laravel-admin/nprogress/nprogress.js"></script>
<script src="<?php echo e(asset('js/pjax.js'), false); ?>"></script>
<script src="<?php echo e(asset('js/core.js?6'), false); ?>"></script>
</body>
</html>