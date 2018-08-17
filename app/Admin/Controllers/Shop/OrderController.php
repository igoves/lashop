<?php
namespace App\Admin\Controllers\Shop;

use App\Http\Controllers\Controller;
//use App\Models\Shop\Category;
//use App\Models\Shop\Product;
use App\Models\Shop\Order;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
//use Encore\Admin\Tree;

class OrderController extends Controller
{
    use ModelForm;
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Orders');
            $content->breadcrumb(
                ['text' => 'Shop'],
                ['text' => 'Orders', 'url' => '/shop/orders']
            );
            $content->body($this->grid());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Order::class, function (Grid $grid) {
            $grid->model()->orderBy('created_at', 'desc');
            $grid->id();
            $grid->name()->editable();
            $grid->email()->editable();
            $grid->phone()->editable();
            $grid->comment()->editable();
            $grid->order()->display(function ($order) {
                return html_entity_decode($order);
            });
            $grid->total()->editable();
            $grid->disableExport();
            $grid->disableFilter();
            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Edit');
            $content->breadcrumb(
                ['text' => 'Shop'],
                ['text' => 'Orders', 'url' => '/shop/orders'],
                ['text' => 'Edit']
            );
            $content->body($this->form()->edit($id));
        });
    }
    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('Create new');
            $content->breadcrumb(
                ['text' => 'Shop'],
                ['text' => 'Orders', 'url' => '/shop/orders'],
                ['text' => 'Create new']
            );
            $content->body($this->form());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Order::form(function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name');
            $form->text('email');
            $form->text('phone');
            $form->textarea('comment')->rows(2);;
            $form->wangeditor('order', 'Order');
            $form->text('total');
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
