<?php

namespace skewer\build\Page\Documents;

use skewer\base\site_module;
use skewer\build\Adm\Documents\models\Documents;
use skewer\build\Tool\LeftList\Group;
use skewer\components\forms\FormBuilder;
use skewer\components\microdata;
use yii\data\ActiveDataProvider;

/**
 * Пользовательский модуль отзывов
 * Class Module.
 */
class Module extends site_module\page\ModulePrototype implements site_module\Ajax
{
    /** @var bool Если =true - выводится блок отзывов, если =false - отзывы с пагинатором */
    public $showList = false;

    /** @var int Максимальная длина отзыва */
    public $maxLen = 500;

    /** @var int Количество записей на главной странице */
    public $onPage = 10;

    /** @var string шаблон детального состояния */
    public $template_detail = 'view.twig';


    public function getList()
    {
        $query = Documents::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $aData = $dataProvider->getModels();

        /** @var Documents[] $aData */
        foreach ($aData as $aItem) {
            $aTmpData = $aItem->toArray();
            $aOut[] = $aTmpData;
        }

        return $aOut;
    }

    /**
     * {@inheritdoc}
     */
    protected function onCreate()
    {
        if ($this->showList) {
            $this->setUseRouting(false);
        }
    }

    public function init()
    {
        $this->maxLen = ($this->maxLen) ? abs($this->maxLen) : 500;

        $this->setParser(parserTwig);

            if (!$this->get('cmd')
                || (
                !$this->get('cmd')
                &&
                $this->zoneType != Group::CONTENT
                &&
                !$this->get('parent_class'))
        ) {
            $this->set('cmd', 'Init');
        }
    }

    public function actionIndex()
    {
        $this->actionInit();
    }

    /**
     * @throws \Exception
     *
     * @return int
     */
    public function actionInit()
    {
        $this->setReviewsWithPaginator();
        $this->setForm();

        return psComplete;
    }

    /**
     * Установить список отзывов с пагинатором
     * Используется:
     * 1. в разделе, образованном от шаблона "Отзывы"
     */

    private function setReviewsWithPaginator()
    {
        $aDocs = $this->getList();
        $this->setData('aDocs', $aDocs);
        $this->setTemplate($this->template_detail);
    }

    /**
     * Отправка формы отзывов.
     *
     * @throws \Exception
     *
     * @return int
     */
    public function actionSendReview()
    {
        $post = $this->getPost();

        foreach ($post as &$psValue) {
            $psValue = strip_tags($psValue);
        }

        $fileEntity = new FileEntity($this->sectionId(), $post);
        $label = $this->get('label') ?: $this->oContext->getLabel();

        $formBuilder = new FormBuilder(
            $fileEntity,
            $this->sectionId(),
            $label
        );

        $ajaxForm = $this->getData('ajax') ?: $fileEntity->formAggregate->result->isPopupResultPage();

        if ($formBuilder->hasSendData() && $formBuilder->validate() && $formBuilder->save()) {
            $formBuilder->setLegalRedirect();

            $aParam = ['form_section' => $this->sectionId()];

            $sAnswer = $formBuilder->buildSuccessAnswer(
                $ajaxForm,
                $this->sectionId(),
                $aParam
            );

            if (!$ajaxForm) {
                if ($formBuilder->canResponse()) {
                    $this->setData('msg', $sAnswer);
                    $this->setData('back_link', 1);
                // Сторонняя результирующая -> редирект на другую страницу
                } elseif ($fileEntity->formAggregate->result->isExternalResultPage()) {
                    $formBuilder->setRedirect();
                }
            }
            $this->setData('msg', $sAnswer);
            $this->setData('back_link', 1);
        } else {
            $this->setData('form', $formBuilder->getFormTemplate());
            if (!$ajaxForm) {
                $this->setReviewsWithPaginator();
            }
        }

        $this->setTemplate($this->template_detail);

        return psComplete;
    }


    /**
     * Показывать форму?
     *
     * @return bool
     */
    /*public function bShowForm()
    {
        return ((($this->getLabel() == DetailPage::LABEL_GOODSREVIEWS) && !$this->bOnlyMicrodata) || $this->getLabel() == 'content') && !$this->hide_form;
    }*/

    /**
     * Установить форму.
     *
     * @throws \Exception
     */
    public function setForm()
    {
        $reviewEntity = new FileEntity($this->sectionId());
//        $reviewEntity->setParamForGoodReview($this->objectId, $this->className);
        $label = $this->get('label') ?: $this->oContext->getLabel();

        $formBuilder = new FormBuilder(
            $reviewEntity,
            $this->sectionId(),
            $label
        );

        $this->setData('form', $formBuilder->getFormTemplate());
    }

}
