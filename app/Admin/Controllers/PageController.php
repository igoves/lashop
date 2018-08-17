<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Models\Page;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PageController extends Controller
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
            $content->header('Pages');
//            $content->description('page description');
            $content->breadcrumb(
                ['text' => 'Pages', 'url' => '/pages']
            );
//            $content->body('hello world');
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
        return Admin::grid(Page::class, function (Grid $grid) {
            $grid->id();
            $grid->title()->editable();
            $grid->slug();
//            $grid->disableActions();
//            $grid->disableBatchDeletion();
            $grid->disableExport();
//            $grid->disableCreation();
            $grid->disableFilter();
            $grid->created_at();
            $grid->updated_at();
            $grid->actions(function ($actions) {
                $actions->prepend('<a href="/'.$actions->row->slug.'.html" target="_blank"><i class="fa fa-eye"></i></a>');
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
                ['text' => 'Pages', 'url' => '/pages'],
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
                ['text' => 'Pages', 'url' => '/pages'],
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
        return Page::form(function (Form $form) {
            $form->text('title')->rules('required');
            $form->text('slug')->rules('required');
            $form->wangeditor('fulldesc', 'Description');
            $form->textarea('meta_desc', 'Meta Description')->rows(2);
            $form->textarea('meta_key', 'Meta Keywords')->rows(2);
//            $form->display('created_at', 'Created At');
//            $form->display('updated_at', 'Updated At');
        });
    }

}