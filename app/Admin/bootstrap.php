<?php
use Encore\Admin\Form;
use App\Admin\Extensions\Form\WangEditor;
use App\Admin\Extensions\Nav\Links;

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Form::forget(['map', 'editor']);

Form::extend('wangeditor', WangEditor::class);

Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
//    $navbar->left(view('admin.search-bar'));
    $navbar->right(view('admin.nav-links'));
});