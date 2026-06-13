<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Shop setting by slug (settings table, cached).
     */
    function setting(string $slug, mixed $default = null): mixed
    {
        return Setting::findBySlug($slug, $default);
    }
}

if (! function_exists('price')) {
    /**
     * Display price: cost × rate, formatted as "12.50".
     */
    function price(float|string $cost): string
    {
        $rate = (float) setting('rate', 1);

        return number_format((float) $cost * $rate, 2, '.', ' ');
    }
}
