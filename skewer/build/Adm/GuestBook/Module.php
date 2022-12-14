<?php

namespace skewer\build\Adm\GuestBook;

use skewer\build\Adm;
use skewer\build\Tool;

/**
 * Проекция редактора баннеров для слайдера в панель управления
 * Class Module.
 */
class Module extends Tool\Review\Module implements Tool\LeftList\ModuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Tool\LeftList\ModulePrototype::updateLanguage();
        parent::init();
    }

    /** {@inheritdoc} */
    public function onCreate()
    {
        $oContext = $this->oContext;

        $oContext->setModuleName('Review');
        $oContext->setModuleWebDir('/skewer/build/Tool/Review');
        $oContext->setModuleLayer('Tool');

        $this->iShowSection = $this->sectionId();
    }
}
