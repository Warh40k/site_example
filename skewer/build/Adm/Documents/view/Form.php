<?php

namespace skewer\build\Adm\Documents\view;

use skewer\base\ft\Editor;
use skewer\build\Adm\Documents\models\Documents;
use skewer\components\ext\view\FormView;

class Form extends FormView
{
    /** @var Documents */
    public $item;

    /**
     * Выполняет сборку интерфейса.
     */
    public function build()
    {
        $this->_form->field('id', 'ID', 'hide')
            ->field('name', \Yii::t('documents', 'field_name'), 'string')
            ->field('description', \Yii::t('documents', 'field_description'), 'string')
            ->field('file', \Yii::t('documents', 'field_file'), Editor::FILE)
            ->fieldSelect('country', \Yii::t('documents', 'field_country'), ["Украина","Россия","Беларусь"])

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
