<?php

namespace skewer\build\Page\Documents;

use skewer\base\log\Logger;
use skewer\base\site;
use skewer\base\site_module;
use skewer\base\SysVar;
use skewer\build\Adm\Documents as DocumentsAdm;
use skewer\build\Adm\Documents\models\Documents;
use skewer\build\Design\Zones;
use skewer\components\auth\CurrentAdmin;
use skewer\components\gallery\Album;
use skewer\components\gallery\Photo;
use skewer\components\GalleryOnPage\Api as GalOnPageApi;
use skewer\components\microdata\reviews\Api as MicroData;
use skewer\components\seo;
use skewer\components\traits\CanonicalOnPageTrait;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Публичный модуль вывода документов
 * Class Module.
 */
class Module extends site_module\page\ModulePrototype
{
    use CanonicalOnPageTrait;

    public $parentSections;
    public $onPage = 10;
    public $template = 'list.twig';
    public $template_detail = 'detail_page.twig';
    public $titleOnMain = 'Документы';
    public $showFuture;
    public $showOnMain;  // отменяет фильтр по разделам
    public $allDocuments;
    public $sortDocuments;
    public $showList;
    public $onMainShowType = 'list';
    public $lengthAnnounceOnMain = null;
    // public $usePageLine = 1;

    private static $showDetailLink = null;

    public $aParameterList = [
        'order' => 'DESC',
        'future' => '',
        'byDate' => '',
        'on_page' => '',
        'on_main' => '',
        'section' => '',
        'all_documents' => '',
    ];

    public $section_all = 0;

    protected function onCreate()
    {
        if ($this->showList) {
            $this->setUseRouting(false);
        }
    }

    public function init()
    {
        $this->onPage = abs($this->onPage);

        $this->setParser(parserTwig);

        $this->aParameterList['on_page'] = $this->onPage;

        if ($this->allDocuments) {
            $this->aParameterList['all_documents'] = 1;
        }
        if ($this->showOnMain) {
            $this->aParameterList['all_documents'] = 1;
        }

        if ($this->parentSections) {
            $this->aParameterList['section'] = $this->parentSections;
        } else {
            $this->aParameterList['section'] = $this->sectionId();
        }

        if ($this->sectionId() == \Yii::$app->sections->main()) {
            $this->aParameterList['on_main'] = 1;
        }
        if ($this->showFuture) {
            $this->aParameterList['future'] = 1;
        }
        if ($this->sortDocuments) {
            $this->aParameterList['order'] = $this->sortDocuments;
        }

        return true;
    }

    // func

    /**
     * Выводит документ по псевдониму.
     *
     * @param $documents_alias
     *
     * @throws NotFoundHttpException
     *
     * @return int
     */
   /* public function actionView($documents_alias)
    {
        $documents = Documents::getPublicDocumentsByAliasAndSec($documents_alias);

        return $this->showOne($documents);
    }*/

    /**
     * Выводит документ по id.
     *
     * @param $id
     *
     * @throws NotFoundHttpException
     *
     * @return int
     */
    public function actionViewById($id)
    {
        $documents = Documents::getPublicDocumentsById($id);
        if (isset($documents['parent_section'], $documents['documents_alias'])) {
            $this->setCanonicalByAlias(
                (int) $documents['parent_section'],
                $documents['documents_alias']
            );
        }


        return $this->showOne($documents);
    }

    /**
     * Выводит документ.
     *
     * @param null|Documents $documents
     *
     * @return int
     *@throws NotFoundHttpException
     *
     */
    public function showOne($documents)
    {

        if (!$documents) {
            throw new NotFoundHttpException();
        }
        if (!$this->canShowDocuments($documents)) {
            throw new NotFoundHttpException();
        }

        \Yii::$app->router->setLastModifiedDate($documents->last_modified_date);


        // меняем заголовок
        site\Page::setTitle($documents->title);

        // добавляем элемент в pathline
        site\Page::setAddPathItem($documents->title, \Yii::$app->router->rewriteURL($documents->getUrl()));

        $this->setData('hideDate', $this->hasHideDatePublication());
        $this->setData('microData', MicroData::microData4Documents($documents));

        $this->setData('documents', $documents);

        $this->setTemplate($this->template_detail);
        return psComplete;
    }

    /**
     * Выводит список документов.
     *
     * @param int $page номер страницы
     * @param string $date фильтр по дате
     *
     * @return int
     */
    public function actionIndex($page = 1, $date = '')
    {
        if (!$this->onPage) {
            return psComplete;
        }

        $this->aParameterList['page'] = $page;

        if (!empty($date)) {
            $sDateFilter = date('Y-m-d', strtotime($date));
            $this->aParameterList['byDate'] = $sDateFilter;
        }

        $iAllSection = 0;
        if ($this->showOnMain) {
            $iAllSection = $this->section_all;
        }

        if ($iAllSection) {
            $this->aParameterList['all_documents'] = 0;
            $this->aParameterList['section'] = $iAllSection;
            $this->setData('section_all', $iAllSection);
        }

        // Получаем список документов
        $dataProvider = Documents::getPublicList($this->aParameterList);
        \Yii::$app->router->setLastModifiedDate(Documents::getMaxLastModifyDate());

        //пагинатор
        $iPage = $dataProvider->getPagination()->page + 1;
        $iCount = $dataProvider->getTotalCount();
        $this->getPageLine($iPage, $iCount, $this->sectionId(), [], ['onPage' => $this->aParameterList['on_page']], 'aPages', !$this->isMainModule());
        $this->showPagination();

        $this->showCarousel();

        $this->setTemplate($this->template);
        $this->setData('aDocuments', $aDocuments);
        $this->setData('titleOnMain', $this->titleOnMain);
        $this->setData('asset_path', $this->getAssetWebDir());
        $this->setData('showDetailLink', self::hasShowDetailLink());
        $this->setData('hideDate', $this->hasHideDatePublication());
        $this->setData('onMainShowType', $this->onMainShowType);
        $this->setData('zone', $this->zoneType);
//        $this->setData('bShowGallery', Api::bShowGalleryInList());
        $this->setData('sFormatImage', $sFormatImage);
        $this->setData('date', $date);
        $this->setData('lengthAnnounceOnMain', $this->lengthAnnounceOnMain);

        return psComplete;
    }


    /**
     * Флаг вывода ссылки "Подробнее".
     *
     * @return bool
     */
    public static function hasShowDetailLink()
    {
        if (self::$showDetailLink === null) {
            self::$showDetailLink = (bool) SysVar::get('Documents.showDetailLink');
        }

        return self::$showDetailLink;
    }

    /**
     * Скрывать дату публикации?
     *
     * @return bool
     */
    public function hasHideDatePublication()
    {
        return (bool) SysVar::get('Documents.hasHideDatePublication', false);
    }

    /**
     * Пагинатор на страницу.
     */
    public function showPagination()
    {
        //выводим пагинацию только в зоне "контент"
        if ($this->zoneType == 'content') {
            $this->setData('showPagination', 1);
        }
    }

    /**
     * Проверяем, нужно ли отдать 404ю вместо документа
     * Для админов показываются как активные, так и неактивные новости
     * @param Documents $documents
     * @return bool
     */
    private function canShowDocuments(Documents $documents): bool
    {
        $bIsAdmin = CurrentAdmin::isLoggedIn();
        return $documents->active || $bIsAdmin;
    }
}
