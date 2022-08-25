<?php

namespace skewer\build\Adm\Documents\view;

use skewer\base\ft\Editor;
use skewer\base\router\Router;
use skewer\base\SysVar;
use skewer\build\Adm\Documents\models\Documents;
use skewer\build\Adm\Tree\ModulePrototype;
use skewer\components\ext\view\FormView;
use skewer\components\gallery\Profile;

class Form extends FormView
{
    /** @var Documents */
    public $item;

    /** @var string */
//    public $sPreviewLink;

    /**
     * Выполняет сборку интерфейса.
     */
    public function build()
    {
        $this->_form->field('id', 'ID', 'hide')
            ->field('name', \Yii::t('documents', 'field_name'), 'string')
            ->field('description', \Yii::t('documents', 'field_description'), 'string')
            ->field('file', \Yii::t('documents', 'field_file'), Editor::FILE)
            ->field('country', \Yii::t('documents', 'field_country'), Editor::STRING)

            ->buttonSave()
            ->buttonSave('saveAndContinue', \Yii::t('documents', 'save_and_continue'))
            ->buttonBack();

        if ($this->item->id) {
            $this->_form
                ->buttonSeparator('->')
                ->buttonDelete();
        }
        $this->_form->setValue($this->item->getAttributes());
    }
}
