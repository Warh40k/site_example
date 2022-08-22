<?php

namespace skewer\build\Adm\Documents;

use skewer\base\ui;
use skewer\build\Adm;
use skewer\build\Adm\Documents\models\Documents;
use Yii;
use yii\base\UserException;

/**
 * Class Module.
 */
class Module extends Adm\Tree\ModulePrototype
{
    // число элементов на страницу
    protected $iOnPage = 20;

    // текущий номер страницы ( с 0, а приходит с 1 )
    protected $iPageNum = 0;

    /** @var int id текущей документа */
    protected $iDocumentsId = 0;

    /**
     * Метод, выполняемый перед action меодом
     *
     * @throws UserException
     */
    protected function preExecute()
    {
        // номер страницы
        $this->iPageNum = $this->getInt('page');
    }

    /**
     * Первичное состояние - спосок документов для раздела.
     */
    protected function actionInit()
    {
        // сбрасываем сохраненный id документа
        $this->setInnerData('id', 0);
        $this->iDocumentsId = 0;
        // -- сборка интерфейса
        $documents = Documents::find()
            ->orderBy(['id' => SORT_ASC])
            ->limit($this->iOnPage)
            ->offset($this->iPageNum * $this->iOnPage)
            ->asArray()
            ->all();

        $iCount = Documents::find()
            ->count();

        /*
         * @var Documents[] $documents
         */

        $this->render(
            new view\Index([
                'items' => $documents,

                'page' => $this->iPageNum,
                'onPage' => $this->iOnPage,
                'total' => $iCount,
            ])
        );
    }

    /**
     * Сохраняет записи из спискового интерфейса.
     */
    protected function actionSaveFromList()
    {
        $iId = $this->getInDataValInt('id');

        $sFieldName = $this->get('field_name');

        /** @var Documents $oRow */
        if (!($oRow = Documents::findOne(['id' => $iId]))) {
            throw new UserException(Yii::t('documents', 'error_row_not_found', [$iId]));
        }
        $oRow->{$sFieldName} = $this->getInDataVal($sFieldName);

        $oRow->save();

        $this->actionInit();
    }

    /**
     * Форма добавления.
     */
    protected function actionNew()
    {
        $documents = Documents::getNewRow();
        $this->render(new view\Form([
//            'sPreviewLink' => $documents->getPreviewLink(),
            'item' => $documents,
        ]));
    }

    /**
     * Форма редактирования.
     */
    protected function actionShow()
    {
        $aData = $this->get('data');

        $iItemId = $aData['id'];
        if (!$iItemId) {
            $iItemId = $this->iDocumentsId;
        }

        /** @var Documents $oDocumentsRow */
        if (!($oDocumentsRow = Documents::findOne(['id' => $iItemId]))) {
            throw new UserException(Yii::t('documents', 'error_row_not_found', [$iItemId]));
        }
        $this->render(new view\Form([
//            'sPreviewLink' => $oDocumentsRow->getPreviewLink(),
            'item' => $oDocumentsRow,
        ]));
    }

    /**
     * Сохранение документа.
     */
    protected function actionSave()
    {
        $this->save();
        $this->actionInit();
    }

    /**
     * Сохранить документ и продолжить редактирование
     * @throws UserException
     * @throws ui\ARSaveException
     */
    protected function actionSaveAndContinue()
    {
        $iDocumentsId = $this->save();
        $this->iDocumentsId = $iDocumentsId;
        $this->actionShow();
    }

    /**
     * Сохранение документа
     */
    private function save()
    {
        // запросить данные
        $aData = $this->get('data', []);
        $iId = $this->getInDataValInt('id');

        // Новая запись?
        $bIsNewRecord = !(bool) $iId;

        if (!$bIsNewRecord) {
            if (!($oDocumentsRow = Documents::findOne(['id' => $iId]))) {
                throw new UserException(Yii::t('documents', 'error_row_not_found', [$iId]));
            }
        } else {
            $oDocumentsRow = Documents::getNewRow(['parent_section' => $this->sectionId()]);
        }

        // Запомним данные до внесения изменений
        $aOldAttributes = $oDocumentsRow->getAttributes();
        // Заполняем запись данными из web-интерфейса
        $oDocumentsRow->setAttributes($aData);

        if (!$oDocumentsRow->save()) {
            throw new ui\ARSaveException($oDocumentsRow);
        }

        return $oDocumentsRow->id;
    }

    /**
     * Удаляет запись.
     */
    protected function actionDelete()
    {
        // запросить данные
        $aData = $this->get('data', []);
        $iItemId = $this->getInDataValInt('id', 0);

        if (!($oNew = Documents::findOne($iItemId))) {
            throw new UserException(Yii::t('documents', 'error_row_not_found', [$iItemId]));
        }
        $oNew->delete();

        // вывод списка
        $this->actionInit();
    }

    /**
     * Установка служебных данных.
     *
     * @param ui\state\BaseInterface $oIface
     */
    protected function setServiceData(ui\state\BaseInterface $oIface)
    {
        // установить данные для передачи интерфейсу
        $oIface->setServiceData([
            'page' => $this->iPageNum,
        ]);
    }
}
