<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Dashboard');
//            $content->description('Description...');

//            $content->row(Dashboard::title());


            $content->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $stats = array();
                    $stats['categories'] = DB::table('shop_categories')->count();
                    $stats['products'] = DB::table('shop_products')->count();
//                $stats['pages'] = DB::table('pages')->count();
                    $stats['orders'] = DB::table('shop_orders')->count();
                    $stats['revenue'] = DB::table('shop_orders')->sum('total');
                    $column->append( view('admin.dashboard', ['orders' => $stats['orders'], 'revenue' => $stats['revenue'], 'products' => $stats['products'], 'categories' => $stats['categories']]) );
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
        });
    }
}
