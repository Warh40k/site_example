<?php

namespace skewer\build\Adm\Documents\models;

use skewer\base\log\Logger;
use skewer\base\router\Router;
use skewer\base\section\Tree;
use skewer\base\site\Site;
use skewer\build\Adm\Documents\Exporter;
use skewer\build\Adm\Documents\Importer;
use skewer\build\Adm\Documents\Search;
use skewer\build\Tool\Rss;
use skewer\build\Tool\SeoGen\exporter\GetListExportersEvent;
use skewer\build\Tool\SeoGen\importer\GetListImportersEvent;
use skewer\components\ActiveRecord\ActiveRecord;
use skewer\components\gallery\Album;
use skewer\components\gallery\Photo;
use skewer\components\seo\Api;
use skewer\components\seo\Service;
use skewer\helpers\Html;
use skewer\helpers\ImageResize;
use skewer\helpers\Transliterate;
use Yii;
use yii\base\ModelEvent;
use yii\base\UserException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "documents".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $file
 *
 * @method static Documents findOne($condition)
 */
class Documents extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'description', 'file', ], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('documents', 'field_id'),
            'name' => Yii::t('documents', 'field_name'),
            'description' => Yii::t('documents', 'field_description'),
            'file' => Yii::t('documents', 'field_file'),
//            'documents_alias' => Yii::t('documents', 'field_alias'),
        ];
    }

/*    public static function getPublicDocumentsByAliasAndSec($sDocumentsAlias)
    {
        return Documents::findOne(['documents_alias' => $sDocumentsAlias]);
    }*/

    public static function getPublicDocumentsById($iDocumentsId)
    {
        return Documents::findOne(['id' => $iDocumentsId]);
    }

/*    public function getFormat_Announce()
    {
        return str_replace('data-fancybox-group="button"', 'data-fancybox-group="documents' . $this->id . '"', $this->announce);
    }*/

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public static function getPublicList($params)
    {
        $query = Documents::find()->orderBy(['id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (isset($params['on_page'])) ? $params['on_page'] : 10,
                'page' => $params['page'] - 1,
            ],
        ]);

        if (isset($params['on_page'])) {
            // добавим рулесы в UrlManager для правильного построителя ЧПУ
            // нужно для коректной генерации пагинатора
            Documents::routerRegister();
        }

        /* Если есть фильтр по дате */
        /*if (isset($params['byDate']) && !empty($params['byDate'])) {
            $query->andFilterWhere(['>', 'publication_date', $params['byDate'] . ' 00:00:00']);
            $query->andFilterWhere(['<', 'publication_date', $params['byDate'] . ' 23:59:59']);
        }*/

       /* if (!$params['all_documents'] && $params['section']) {
            $query->andFilterWhere(['parent_section' => $params['section']]);
        }

        if ($params['on_main']) {
            $query->andFilterWhere(['on_main' => 1]);
        }

        $aSections = Tree::getAllSubsection(\Yii::$app->sections->languageRoot());
        $query->andFilterWhere(['parent_section' => array_intersect_key($aSections, Tree::getVisibleSections())]);

        if ($params['future']) {
            $query->andFilterWhere(['>', 'publication_date', date('Y-m-d H:i:s', time())]);
        }*/

        return $dataProvider;
    }

    public static function routerRegister()
    {
        $url = Yii::$app->getRequest()->getPathInfo();
        $url = preg_replace('/page(.)*/', '${2}', $url);

        Yii::$app->getUrlManager()->addRules([$url . 'page/<page:[\w\.]+>' => 'site/index']);
    }

    public static function getNewRow($aData = [])
    {
        $oRow = new Documents();

        $oRow->name = '';
        $oRow->description = '';
        $oRow->file = '';

        if ($aData) {
            $oRow->setAttributes($aData);
        }

        return $oRow;
    }

    public function initSave()
    {
       /* if (!$this->documents_alias) {
            $sValue = Transliterate::change($this->title);
        } else {
            $sValue = Transliterate::change($this->documents_alias);
        }

        // приводим к нужному виду
        $sValue = Transliterate::changeDeprecated($sValue);
        $sValue = Transliterate::mergeDelimiters($sValue);
        $sValue = trim($sValue, '-');

        // к числам прибавляем префикс
        if (is_numeric($sValue)) {
            $sValue = 'documents-' . $sValue;
        }

        try {
            $this->documents_alias = Service::generateAlias($sValue, $this->id, $this->parent_section, 'Documents');
        } catch (UserException $e) {
            $this->addErrors(['documents_alias' => $e->getMessage()]);

            return false;
        }

        // format wyswyg fields
        if ($this->full_text && $this->parent_section) {
            $this->full_text = ImageResize::wrapTags($this->full_text, $this->parent_section);
        }

        if ($this->announce && $this->parent_section) {
            $this->announce = ImageResize::wrapTags($this->announce, $this->parent_section);
        }

        $aFieldsLink = ['hyperlink', 'source_link'];

        foreach ($aFieldsLink as $item) {
            if (!empty($this->{$item}) && (mb_strpos($this->{$item}, 'http') === false) and !(in_array(mb_substr($this->{$item}, 0, 1), ['/', '[']))) {
                $this->{$item} = 'http://' . $this->{$item};
            }
        }

        if (!$this->publication_date || ($this->publication_date == 'null')) {
            $this->publication_date = date('Y-m-d H:i:s', time());
        }

        $this->last_modified_date = date('Y-m-d H:i:s', time());*/

        return parent::initSave();
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        /*$oSearch = new Search();
        $oSearch->updateByObjectId($this->id);

        if (($changedAttributes) && !(isset($changedAttributes['on_main']) && (count($changedAttributes) == 1))) {
            if (in_array($this->parent_section, Rss\Api::getSectionsIncludedInRss())) {
                Yii::$app->trigger(Rss\Api::EVENT_REBUILD_RSS);
            }
        }*/
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        parent::afterDelete();

        /*// удаление SEO данных
        Api::del('documents', $this->id);

        Album::removeAlbum($this->gallery);
        Photo::removeImage($this->author_photo);

        $oSearch = new Search();
        $oSearch->deleteByObjectId($this->id);*/

        Yii::$app->router->updateModificationDateSite();

      /*  if (in_array($this->parent_section, Rss\Api::getSectionsIncludedInRss())) {
            Yii::$app->trigger(Rss\Api::EVENT_REBUILD_RSS);
        }*/
    }

    /**
     * Удаление всех новостей для раздела.
     *
     * @param ModelEvent $event
     */
    public static function removeSection(ModelEvent $event)
    {
//        self::deleteAlbumsBySectionId($event->sender->id);
        Logger::dump(self::find()->all());
//        self::deleteAll(['parent_section' => $event->sender->id]);
    }

    /**
     * Класс для сборки списка автивных поисковых движков.
     *
     * @param \skewer\components\search\GetEngineEvent $event
     */
    public static function getSearchEngine(\skewer\components\search\GetEngineEvent $event)
    {
        $event->addSearchEngine(Search::className());
    }

    /**
     * Возвращает максимальную дату модификации сущности.
     *
     * @return array|bool
     */
    /*public static function getMaxLastModifyDate()
    {
        return (new \yii\db\Query())->select('MAX(`last_modified_date`) as max')->from(self::tableName())->one();
    }*/

    /**
     * Вернет урл новости.
     *
     * @return string
     */
    /*public function getUrl()
    {
        if ($this->hyperlink) {
            return $this->hyperlink;
        }
        $hrefParam = $this->documents_alias ? "documents_alias={$this->documents_alias}" : "documents_id={$this->id}";

        return "[{$this->parent_section}][Documents?" . $hrefParam . ']';
    }*/

    /**
     * Обрезает текст аннонса до указанной длины
     * @param null $textLength
     * @return string
     */
    public function getTruncateAnnounce($textLength = null)
    {
        if (empty($textLength)) {
            return $this->announce;
        }

        return StringHelper::truncate($this->announce, (int)$textLength, ' ...');
    }

    /**
     * Новость имеет ссылку на детальную страницу?
     *
     * @return bool
     */
    /*public function hasDetailLink()
    {
        return Html::hasContent($this->full_text) || $this->hyperlink;
    }*/

    /**
     * Ведет ли ссылка на внешний ресурс
     *
     * @return bool
     */
    /*public function isExternalHyperLink(): bool
    {
        if (empty($this->hyperlink)) {
            return false;
        }

        $domainParams = parse_url(Site::httpDomain());
        $hyperlinkParams = parse_url($this->hyperlink);

        if (isset($domainParams['host']) && isset($hyperlinkParams['host']) && $domainParams['host'] !== $hyperlinkParams['host']) {
            return true;
        }

        return false;
    }*/

    /**
     * Набивает внутренний массив события $oEvent последними новостями.
     *
     * @param Rss\GetRowsEvent $oEvent
     */
    /*public static function getRssRows(Rss\GetRowsEvent $oEvent)
    {
        $aSections = array_intersect(Tree::getVisibleSections(), Rss\Api::getSectionsIncludedInRss());

        if (!$aSections) {
            return;
        }

        $aRecords = self::find()
            ->where('announce <> :emptyString', ['emptyString' => ''])
            ->andWhere(['active' => 1])
            ->andWhere(['parent_section' => $aSections])
            ->orderBy(['publication_date' => SORT_DESC])
            ->limit(Rss\Api::COUNT_RECORDS_PER_MODULE)
            ->all();

        $oEvent->aRows = ArrayHelper::merge($oEvent->aRows, $aRecords);
    }*/

    /**
     * Удалит альбомы новостей по id раздела.
     *
     * @param int $iSectionId - id раздела
     */
   /* private static function deleteAlbumsBySectionId($iSectionId)
    {
        $oQuery = Documents::find()
            ->where(['parent_section' => $iSectionId]);

        foreach ($oQuery->each() as $oNew) {
            Album::removeAlbum($oNew->gallery);
        }
    }*/

    /**
     * Регистрирует класс Importer, в списке импортёров события $oEvent.
     *
     * @param GetListImportersEvent $oEvent
     */
    /*public static function getImporter(GetListImportersEvent $oEvent)
    {
        $oEvent->addImporter(Importer::className());
    }*/

    /**
     * Регистрирует класс Exporter, в списке экпортёров события $oEvent.
     *
     * @param GetListExportersEvent $oEvent
     */
    /*public static function getExporter(GetListExportersEvent $oEvent)
    {
        $oEvent->addExporter(Exporter::className());
    }*/

    /**
     * Возвращает html ссылки на предварительный просмотр
     * @return string
     */
    /*public function getPreviewLink(): string
    {
        $sLinkHtml = '';
        $sItemUrl = Router::rewriteURL($this->getUrl());
        $sUrlText = \Yii::t('documents', 'field_preview_url');
        if (!$this->id) {
            $sLinkHtml = \Yii::t('documents', 'preview_no_save_error');
        } elseif (!Tree::isSectionVisible($this->parent_section)) {
            $sLinkHtml = \Yii::t('documents', 'preview_visible_error');
        } else {
            if ($this->hyperlink) {
                $sLinkHtml = \Yii::t('documents', 'preview_hyperlink_error');
                $sLinkHtml .= "<br>";
                $sLinkHtml .= "<a href='$this->hyperlink' target='_blank'>$sUrlText</a>";
            } else {
                $sLinkHtml .= "<a href='$sItemUrl' target='_blank'>$sUrlText</a>";
            }
        }
        return $sLinkHtml;
    }*/
}
