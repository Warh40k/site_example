<?php

namespace skewer\build\Adm\Documents\view;

use skewer\components\ext\view\ListView;

class Index extends ListView
{
    /** @var array Documents[] */
    public $items = [];

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $this->_list
            ->field('id', 'ID', 'string', ['listColumns' => ['flex' => 1]])
            ->field('name', \Yii::t('documents', 'field_name'), 'string')

            ->buttonRowUpdate()
            ->buttonRowDelete()
            ->buttonAddNew('new');

        $this->_list->setValue($this->items, $this->onPage, $this->page, $this->total);

        $this->_list->setEditableFields(['active', 'on_main'], 'saveFromList');
    }
}
