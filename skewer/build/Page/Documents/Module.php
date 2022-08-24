<?php

namespace skewer\build\Page\Documents;

use skewer\base\log\Logger;
use skewer\base\site_module;
use skewer\base\SysVar;
use skewer\build\Adm\Documents\models\Documents;
use skewer\build\Adm\GuestBook\models\GuestBook;
use skewer\build\Page\CatalogViewer\Module as CatalogViewerModule;
use skewer\build\Page\CatalogViewer\State\DetailPage;
use skewer\build\Page\Documents\ReviewEntity;
use skewer\build\Tool\LeftList\Group;
use skewer\build\Tool\Review\Api;
use skewer\components\catalog\GoodsSelector;
use skewer\components\forms\FormBuilder;
use skewer\components\GalleryOnPage;
use skewer\components\microdata;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Пользовательский модуль отзывов
 * Class Module.
 */
class Module extends site_module\page\ModulePrototype implements site_module\Ajax
{
    /** @var null Имя таба */
    public $sTabName;

    /** @var int Номер страницы из модуля CatalogViewer - DetailPage */
    public $iPage = 0;

    /** @var int id товара */
    public $objectId;

    /** @var string Указывает тип родитеской сущности */
    public $className = '';

    /** @var bool Если =true - выводится блок отзывов, если =false - отзывы с пагинатором */
    public $showList = false;

    /** @var string Заголовок блока отзывов */
    public $titleOnMain = '';

    /** @var int Максимальная длина отзыва */
    public $maxLen = 500;

    /** @var int Ид раздела из которого будут выбраны отзывы */
    public $section_id;

    /** @var int Количество записей на главной странице */
    public $onPage = 10;

    /** @var int Количество записей на внутренних страницах */
    public $onPageContent = 10;

    /** @var bool Выводить сначала отзывы, а затем форму(при =0)? */
    public $revert = 0;

    /** @var int Не выводить форму? */
    public $hide_form = 0;

    /** @var bool Отдать только микроразметку? */
    public $bOnlyMicrodata = false;

    /** @var int Показать звёзды голосования в списке отзывов? Если значение <0, то параметр будет браться глобальный параметр */
    public $rating = -1;

    /** @var string Шаблон списка отзывов. Если шаблон не указан, то он определяется динамически */
    public $template = '';

    /** @var string шаблон детального состояния */
    public $template_detail = 'view.twig';

    /** @var string Тип вывода */
    public $typeShow;

    /** @var Documents[] Список документов */
    public $aDocs;
    /**
     * @return mixed
     */
    public function getList($iPage)
    {
        $query = Documents::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->onPage,
                'page' => $iPage - 1,
            ],
        ]);

        $aData = $dataProvider->getModels();

        /** @var Documents[] $aData */
        foreach ($aData as $aItem) {
            $aTmpData = $aItem->toArray();
            $aOut[] = $aTmpData;
        }

        $iTotalCount = $dataProvider->getTotalCount();

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
        //Номер текущей страницы
        $this->iPage = ($this->iPage) ? $this->iPage : $this->getInt('page', 1);

        // Количество записей на странице(в блоке)
        $this->onPage = ($this->sectionId() != \Yii::$app->sections->main())
            ? abs($this->onPageContent)
            : abs($this->onPage);

        $this->maxLen = ($this->maxLen) ? abs($this->maxLen) : 500;

        $this->setParser(parserTwig);

        if ($this->bOnlyMicrodata) {
            $this->set('cmd', 'getMicroData');
        } elseif (
            !$this->get('cmd')
            || (
                !$this->get('cmd')
                &&
                $this->zoneType != Group::CONTENT
                &&
                !$this->get('parent_class')
            )
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

        // Блок отзывов на главной и других страницах(без пагинатора и формы)
        if ($this->showList) {
            $this->setReviewsBlock();
        // Отзывы в разделе, отзывы в табах товара(с пагинатором и формой)
        } else {
            $this->setReviewsWithPaginator();
            $this->setForm();
        }

        return psComplete;
    }

    /**
     * Установить список отзывов с пагинатором
     * Используется:
     * 1. в разделе, образованном от шаблона "Отзывы"
     * 2. в табах товара.
     */
    private function setReviewsWithPaginator()
    {
        $iTotalCount = 0;

        $aDocs = $this->getList($this->iPage);
        $this->setData('aDocs', $aDocs);

        $this->setPaginatorPage($aDocs, $this->iPage, $iTotalCount);

        if ($this->template) {
            $sTemplate = $this->template;
        } else {
            if ($this->zoneType == Group::CONTENT) {
                $sTemplate = Api::$aTypeShowReviews[Group::CONTENT]['list']['file'];
            } else {
                $sTemplate = $this->getTemplateReview();
            }
        }

        $sViewReviews = site_module\Parser::parseTwig(
            $sTemplate,
            [
            'items' => $aDocs,
            'aPages' => $this->getData('aPages'),
        ],
            __DIR__ . '/templates'
        );

        $this->setData('sViewReviews', $sViewReviews);

        $this->setTemplate($this->template_detail);
    }

    /**
     * Доп. адаптивность для шаблона одиночка не должна быть.
     *
     * @return string
     */
    private function getSettingsGalOnPage()
    {
        $sTemplate = $this->getTemplateReview();

        $aTpl2EntityName = [
            'content_carousel.twig' => 'Review',
            'content_gray.twig' => 'ReviewGray',
            'content_bubble.twig' => 'ReviewBubble',
            'content_single.twig' => 'ReviewSingle',
        ];

        if (!isset($aTpl2EntityName[$sTemplate])) {
            return false;
        }

        $sEntityName = $aTpl2EntityName[$sTemplate];

        $sSettings = GalleryOnPage\Api::getSettingsByEntity($sEntityName, true);

        return $sSettings;
    }

    /**
     * Установить данные пагинатора.
     *
     * @param array $aData - массив отзывов
     * @param int $iPage - номер текущей страницы
     * @param int $iTotalCount - общее количество записей
     */
    private function setPaginatorPage($aData, $iPage, $iTotalCount)
    {
        $aURLParams = [];
        // параметры для построение пагинации для табов
        if ($this->sTabName !== null) {
            $aURLParams = [
                'goods-alias' => GoodsSelector::getGoodsAlias($this->objectId),
                'tab' => $this->sTabName,
            ];

            $this->oContext->sClassName = CatalogViewerModule::className();
        }
        // генерируем пагинацию только когда есть данные
        if (count($aData)) {
            $bHideCanonicalPagination = $this->bOnlyMicrodata || !$this->isMainModule();
            $this->getPageLine(
                $iPage,
                $iTotalCount,
                $this->sectionId(),
                $aURLParams,
                ['onPage' => $this->onPage],
                'aPages',
                $bHideCanonicalPagination
            );
        }
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

        $reviewEntity = new ReviewEntity($this->sectionId(), $post);
//        $reviewEntity->setParamForGoodReview($this->objectId, $this->className);
        $label = $this->get('label') ?: $this->oContext->getLabel();

        $formBuilder = new FormBuilder(
            $reviewEntity,
            $this->sectionId(),
            $label
        );

        $ajaxForm = $this->getData('ajax') ?: $reviewEntity->formAggregate->result->isPopupResultPage();

        if ($formBuilder->hasSendData() && $formBuilder->validate() && $formBuilder->save()) {
            $formBuilder->setLegalRedirect();

            $aParam = ['form_section' => $this->sectionId()];
//            $aParam['answer_review'] = !$ajaxForm && $reviewEntity->isGoodReview();

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
                } elseif ($reviewEntity->formAggregate->result->isExternalResultPage()) {
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
    public function bShowForm()
    {
        return ((($this->getLabel() == DetailPage::LABEL_GOODSREVIEWS) && !$this->bOnlyMicrodata) || $this->getLabel() == 'content') && !$this->hide_form;
    }

    /**
     * Установить форму.
     *
     * @throws \Exception
     */
    public function setForm()
    {
        $reviewEntity = new ReviewEntity($this->sectionId());
//        $reviewEntity->setParamForGoodReview($this->objectId, $this->className);
        $label = $this->get('label') ?: $this->oContext->getLabel();

        $formBuilder = new FormBuilder(
            $reviewEntity,
            $this->sectionId(),
            $label
        );

        $this->setData('form', $formBuilder->getFormTemplate());
        $this->setData('revert', $this->revert);
    }

    /**
     * Получение шаблона отзывов.
     *
     * @return string
     */
    public function getTemplateReview()
    {
        $sZone = ($this->zoneType && $this->zoneType != Group::CONTENT) ? 'column' : Group::CONTENT;

        return ArrayHelper::getValue(
            Api::$aTypeShowReviews,
            [$sZone, $this->typeShow, 'file'],
            ''
        );
    }
}
