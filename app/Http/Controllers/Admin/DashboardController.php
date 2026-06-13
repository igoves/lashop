<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use App\Models\Page;
use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class DashboardController extends AdminController
{
    public function index(): View
    {
        $counts = [
            'orders' => Order::count(),
            'categories' => Category::count(),
            'products' => Product::count(),
            'brands' => Brand::count(),
            'pages' => Page::count(),
            'news' => News::count(),
        ];

        // Sales chart: orders per month for last 12 months
        $chartLabels = [];
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartLabels[] = $date->format('M Y');
            $chartData[] = (int) Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total');
        }

        // Notepad
        $notepad = User::find(auth()->id())->dashboard_notepad ?? '';

        return view('admin.dashboard', compact('counts', 'chartLabels', 'chartData', 'notepad'));
    }

    public function saveNotepad(Request $request): Response
    {
        $user = $request->user();
        $user->dashboard_notepad = $request->input('content', '');
        $user->save();

        return response()->json(['ok' => true]);
    }
}
