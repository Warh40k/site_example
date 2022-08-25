<?php

namespace skewer\build\Adm\Documents\models;

use skewer\base\log\Logger;
use skewer\build\Adm\Documents\Search;
use skewer\components\ActiveRecord\ActiveRecord;
use Yii;
use yii\base\ModelEvent;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "documents".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $file
 * @property string $country
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
            [['name', 'description', 'file', 'country' ], 'string'],
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
            'country' => Yii::t('documents', 'field_country')
        ];
    }


    public static function getPublicDocumentsById($iDocumentsId)
    {
        return Documents::findOne(['id' => $iDocumentsId]);
    }

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
        return parent::initSave();
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->router->updateModificationDateSite();
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
}
