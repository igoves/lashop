<?php

namespace App\Admin\Sections;

use AdminDisplay;
use AdminColumn;
use AdminForm;
use AdminFormElement;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Products
 *
 * @property \App\Admin\Model\Product $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Products extends Section
{
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::datatablesAsync()->setColumns([
            AdminColumn::link('name')->setLabel('Name')->setWidth('400px'),
            AdminColumn::text('cost')->setLabel('Cost'),
        ])->paginate(15);
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {

//        dd($_FILES);
//        dd($constraint);

        return AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Name')->required(),
            AdminFormElement::text('slug', 'Slug')->required(),
//            AdminFormElement::textarea('desc', 'Description')->setRows(10),
            AdminFormElement::wysiwyg('desc', 'Description'),
            AdminFormElement::text('cat_id', 'Category')->required(),
//            AdminFormElement::text('image', 'Image')->required(),
            AdminFormElement::image('image', 'Image')->setUploadSettings([
//                'orientate' => [],
//                'resize' => [1280, null, function ($constraint) {
//                    $constraint->upsize();
//                    $constraint->aspectRatio();
//                }],
                'fit' => [640, 480, function ($constraint) {
                    $constraint->upsize();
                    $constraint->aspectRatio();
                }]
            ]),
            AdminFormElement::text('cost', 'Cost')->required(),
            AdminFormElement::textarea('meta_desc', 'Meta Description')->setRows(2),
            AdminFormElement::textarea('meta_key', 'Meta Keywords')->setRows(2)
        );
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }

    /**
     * @return void
     */
    public function onDelete($id)
    {


        // todo: remove if unused
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // todo: remove if unused
    }
}
