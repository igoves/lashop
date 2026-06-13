<?php

namespace App\View\Components;

use App\Models\Page;
use App\Models\Shop\Category;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Menu extends Component
{
    public function __construct(public bool $mobile = false) {}

    public function render(): View
    {
        return view('components.menu', [
            'categories' => Category::tree(),
            'pages' => Page::all_cached()->filter(fn ($p) => $p->show_in_menu)->values(),
        ]);
    }
}
