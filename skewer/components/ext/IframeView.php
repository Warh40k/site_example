<?php

namespace skewer\components\ext;

use skewer\base\ui;
use skewer\build\Cms;

/**
 * Тип автопостроителя для вывода текстовых наборов данных
 * (не редактируемых пар название-значение).
 *
 * Задается модель для автоматического подтягивания имен полей
 * В выво попадают только те поля, которые явно есть в массиве значений,
 * а не все из модели как в форме
 */
class IframeView extends ModelPrototype
{
    protected function getDefaultFieldsSet()
    {
        return '';
    }

    public function getInterfaceArray()
    {
        return [];
    }

    public function getComponentName()
    {
        return 'Iframe';
    }

    public function setInterfaceData(Cms\Frame\ModulePrototype $oModule)
    {


        parent::setInterfaceData($oModule); // TODO: Change the autogenerated stub
    }


}
