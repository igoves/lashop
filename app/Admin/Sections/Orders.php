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
 * Class Orders
 *
 * @property \App\Admin\Model\Order $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Orders extends Section
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
            AdminColumn::link('name')->setLabel('Name'),
            AdminColumn::text('email')->setLabel('Email'),
            AdminColumn::text('phone')->setLabel('Phone'),
            AdminColumn::text('order')->setLabel('Order'),
            AdminColumn::text('total')->setLabel('Total'),
        ])->paginate(15);
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        return AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Name')->required(),
            AdminFormElement::text('email', 'Email'),
            AdminFormElement::text('phone', 'Phone'),
            AdminFormElement::textarea('comment', 'Comment')->setRows(2),
            AdminFormElement::textarea('order', 'Order')->setRows(10),
            AdminFormElement::text('total', 'Total')
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
