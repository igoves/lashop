<?php

use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Frontend\BrandController as FrontendBrandController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\Shop\CartController;
use App\Http\Controllers\Frontend\Shop\CategoryController;
use App\Http\Controllers\Frontend\Shop\OrderController;
use App\Http\Controllers\Frontend\Shop\ProductController;
use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart', [CartController::class, 'store'])->name('cart.store');
Route::delete('cart/{id}', [CartController::class, 'destroy'])
    ->whereNumber('id')->name('cart.destroy');

Route::post('search', [SearchController::class, 'store'])
    ->middleware('throttle:30,1')->name('search');
Route::get('search/{story}', [SearchController::class, 'index'])->name('search.index');

Route::post('orders', [OrderController::class, 'store'])
    ->middleware('throttle:5,1')->name('orders.store');
Route::get('order/success', [OrderController::class, 'success'])->name('orders.success');

Route::get('brands', [FrontendBrandController::class, 'index'])->name('brands.index');
Route::get('brands/{slug}', [FrontendBrandController::class, 'show'])->name('brands.show');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1');

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])
        ->middleware('throttle:5,1');

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('dashboard/notepad', [DashboardController::class, 'saveNotepad'])->name('dashboard.saveNotepad');
    Route::patch('settings/bulk', [SettingController::class, 'bulkUpdate'])->name('settings.bulkUpdate');
    Route::get('settings/profile', [SettingController::class, 'profile'])->name('settings.profile');
    Route::patch('settings/profile', [SettingController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::resource('settings', SettingController::class)->only(['index', 'edit', 'update']);
    Route::resource('pages', AdminPageController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('brands', AdminBrandController::class)->except(['show']);
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('news', AdminNewsController::class)->except(['show']);
    Route::get('users', [UserController::class, 'index'])->name('users.index');
});

// News
Route::get('news', [NewsController::class, 'index'])->name('news.index');
Route::get('news/{slug}', [NewsController::class, 'show'])->name('news.show');

// product: /{id}-{slug}
Route::get('{id}-{slug}', [ProductController::class, 'show'])
    ->where('id', '[0-9]+')->name('products.show');

// static pages: /{slug}.html
Route::get('{slug}.html', [PageController::class, 'index'])->name('pages.index');

// categories (catch-all, declared last): /{path} with nesting a/b/c
Route::get('{path}', [CategoryController::class, 'show'])
    ->where('path', '[a-zA-Z0-9/_-]+')->name('categories.show');
