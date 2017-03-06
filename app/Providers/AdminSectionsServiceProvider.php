<?php

namespace App\Providers;

use SleepingOwl\Admin\Contracts\Widgets\WidgetsRegistryInterface;
use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $widgets = [
        \App\Admin\Widgets\NavigationUserBlock::class
    ];

    /**
     * @var array
     */
    protected $sections = [
        \App\Admin\Model\Product::class => 'App\Admin\Sections\Products',
        \App\Admin\Model\Category::class => 'App\Admin\Sections\Categories',
        \App\Admin\Model\Order::class => 'App\Admin\Sections\Orders',
        \App\Admin\Model\Page::class => 'App\Admin\Sections\Pages',
        \App\Admin\Model\Setting::class => 'App\Admin\Sections\Settings',
    ];


    /**
     * @param WidgetsRegistryInterface $widgetsRegistry
     */
    public function registerViews(WidgetsRegistryInterface $widgetsRegistry)
    {
        foreach ($this->widgets as $widget) {
            $widgetsRegistry->registerWidget($widget);
        }
    }

    /**
     * Register sections.
     *
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
        parent::boot($admin);

        $this->loadViewsFrom(base_path("resources/views"), 'admin');
        $this->app->call([$this, 'registerViews']);

    }
}
