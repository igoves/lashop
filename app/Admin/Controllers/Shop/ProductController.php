<?php
namespace App\Admin\Controllers\Shop;
use App\Http\Controllers\Controller;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;
class ProductController extends Controller
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
            $content->header('Products');
            $content->breadcrumb(
                ['text' => 'Shop'],
                ['text' => 'Products', 'url' => '/shop/products']
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
        return Admin::grid(Product::class, function (Grid $grid) {

            $grid->id();
            $grid->title()->editable();
            $grid->slug();
//            $grid->photo()->image();
            $grid->cost();
//            $grid->disableActions();
//            $grid->disableBatchDeletion();
            $grid->disableExport();
//            $grid->disableCreation();
            $grid->disableFilter();
            $grid->created_at();
            $grid->updated_at();
            $grid->actions(function ($actions) {
                $actions->prepend('<a href="/'.$actions->row->id.'-'.$actions->row->slug.'" target="_blank"><i class="fa fa-eye"></i></a>');
            });
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
                ['text' => 'Products', 'url' => '/shop/products'],
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
                ['text' => 'Products', 'url' => '/shop/products'],
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
        return Product::form(function (Form $form) {
            $form->display('id', 'ID');
            $form->text('title')->rules('required');
            $form->text('slug')->rules('required');
            $form->select('cat_id', 'Category')->options(Category::all()->pluck('title','id'));
            $form->image('photo');
            $form->wangeditor('fulldesc', 'Description');
            $form->text('cost');
            $form->textarea('meta_desc', 'Meta Description')->rows(2);
            $form->textarea('meta_key', 'Meta Keywords')->rows(2);
            $form->switch('status', 'Active');
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}