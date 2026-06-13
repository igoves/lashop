<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', fn (User $user) => $user->is_admin);

        View::composer('frontend.layouts.app', function ($view) {
            $view->with('footerPages', Page::all_cached()->filter(fn ($p) => $p->show_in_footer)->values());
        });
    }
}
