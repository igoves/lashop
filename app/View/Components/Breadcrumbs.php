<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Breadcrumbs component. Accepts array [['title' => ..., 'url' => ...], ...];
 * the last item is the current page (no link).
 */
class Breadcrumbs extends Component
{
    /**
     * @param  array<int, array{title: string, url?: string|null}>  $items
     */
    public function __construct(public array $items = []) {}

    public function render(): View
    {
        return view('components.breadcrumbs');
    }
}
