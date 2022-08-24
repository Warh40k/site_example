<?php

declare(strict_types=1);

namespace skewer\build\Page\Documents;

use skewer\base\log\Logger;
use skewer\base\section\Tree;
use skewer\base\site\Site;
use skewer\build\Adm\Gallery\Api as GalleryApi;
use skewer\build\Adm\Documents\models\Documents;
use skewer\build\Tool\Review\Api;
use skewer\components\catalog\GoodsSelector;
use skewer\components\forms\components\fields\File;
use skewer\components\forms\components\fields\Input;
use skewer\components\forms\components\fields\Rating;
use skewer\components\forms\components\fields\Textarea;
use skewer\components\forms\components\TemplateForm;
use skewer\components\forms\components\TemplateLetter;
use skewer\components\forms\components\typesOfValid\Email;
use skewer\components\forms\components\typesOfValid\Text;
use skewer\components\forms\components\typesOfValid\File as FileTypeOfValid;
use skewer\components\forms\entities\BuilderEntity;
use skewer\components\forms\forms\FieldAggregate;
use skewer\components\forms\forms\FormAggregate;
use skewer\components\forms\forms\HandlerTypeForm;
use skewer\components\forms\forms\SettingsFieldForm;
use skewer\components\gallery\Album;
use skewer\components\gallery\Format;
use skewer\components\gallery\Photo;
use skewer\components\gallery\Profile;
use skewer\components\i18n\ModulesParams;
use skewer\helpers\Files;
use skewer\helpers\Image;
use skewer\helpers\Mailer;
use yii\helpers\ArrayHelper;

/**
 * This is parameters of required fields for this form.
 *
 * @property string $name
 * @property string $email
 * @property string $content
 * @property FormAggregate $formAggregate
 * @property FieldAggregate[] $fields
 */
class ReviewEntity extends BuilderEntity
{
    public $cmd = 'sendReview';
    public $parent = 0;
    public $parent_class = '';
    public $rating = 0;
    public $redirectKeyName = 'review';

    /** @var Documents $_documents */
    private $_documents;
    /** @var int $_idSection Родительский раздел */
    private $_idSection;

    private $_objectId = 0;

    private $_parentClass;

    protected static $fieldsForCreatedForm = [
        [
            'settings' => [
                'slug' => 'name',
                'title' => 'review.field_name',
                'required' => 1,
                'labelPosition' => SettingsFieldForm::LABEL_POSITION_TOP,
                'newLine' => 1,
            ],
            'type' => [
                'name' => Input::class,
                'typeOfValid' => Text::class,
            ],
        ],
        [
            'settings' => [
                'slug' => 'description',
                'title' => 'review.field_description',
                'required' => 1,
                'labelPosition' => SettingsFieldForm::LABEL_POSITION_TOP,
                'newLine' => 0,
            ],
            'type' => [
                'name' => Input::class,
                'typeOfValid' => Email::class,
            ],
        ],
        [
            'settings' => [
                'slug' => 'file',
                'title' => 'review.field_city',
                'required' => 0,
                'labelPosition' => SettingsFieldForm::LABEL_POSITION_TOP,
                'newLine' => 0,
            ],
            'type' => [
                'name' => File::class,
                'typeOfValid' => FileTypeOfValid::class,
            ],
        ],
    ];

    public function __construct(
        int $idSection = 0,
        array $innerData = [],
        array $config = []
    ) {
        $this->_idSection = $idSection;
        $this->_documents = Documents::getNewRow($innerData);
        parent::__construct($innerData, $config);
    }

    public static function tableName(): string
    {
        return 'forma-zagruzki-dokumenta';
    }

    /**
     * Дополнительные параметры, которые нужно учитывать если это отзыв к товару.
     *
     * @param int $objectId
     * @param string $parentClass
     */
/*    public function setParamForGoodReview(
        int $objectId = null,
        string $parentClass = ''
    ) {
        $this->_objectId = $objectId;
        $this->_parentClass = $parentClass;
    }*/

    /**
     * @throws \Exception
     * @throws \ReflectionException
     * @throws \yii\base\UserException
     */
    public static function createTable()
    {
        $formAggregate = new FormAggregate();
        $formAggregate->settings->title = \Yii::t('review', 'form_title');
        $formAggregate->settings->slug = self::tableName();
        $formAggregate->settings->system = 1;
        $formAggregate->settings->button = 'auth.authLoginButton';
        $formAggregate->settings->emailInReply = false;

        $formAggregate->settings->showHeader = 0;
        $formAggregate->protection->captcha = false;

        $formAggregate->answer->title = \Yii::t('review', 'send_msg');

        $formAggregate->handler->type = HandlerTypeForm::HANDLER_TO_METHOD;
        $formAggregate->handler->value = self::class;

        $formAggregate->save();
        $formAggregate->saveExtraData();

        self::createFields($formAggregate->getIdForm());
    }

    /**
     * @param int $idAlbum
     *
     * @throws \ReflectionException
     * @throws \yii\base\UserException
     *
     * @return bool
     */
    public function save(int $idAlbum = 0): bool
    {
        $photoGallery = $this->getField('photo_gallery');
/*        if ($photoGallery && !$this->addImage($photoGallery)) {
            return false;
        }*/
        $privatePath = PRIVATE_FILEPATH . $this->getField('file')->getPathToFormFiles();
        $publicPath = FILEPATH . $this->getField('file')->getPathToFormFiles();
        Logger::dump("Пути до файлов");
        Logger::dump($privatePath);
        Logger::dump($publicPath);
        Logger::dump(WEBPATH . $publicPath);
        if(!file_exists($publicPath)) {
            mkdir($publicPath, 0777, true);
        }
        copy($privatePath . $this->getValues()['file'], $publicPath . $this->getValues()['file']);
        $this->_documents->file = ltrim($publicPath, WEBPATH) . $this->getValues()['file'];

        if (!$this->_documents->save()) {
            return false;
        }

        return parent::save();
    }

    public function setAddParamsForShowForm(TemplateForm &$templateForm)
    {
        $parent = $this->_objectId ?: $this->_idSection;

        $tagAction = Tree::getSectionAliasPath(
                $this->_idSection,
                true
            ) . 'response/';

        $templateForm->tagAction = $tagAction;

        $inputParams = [
            'parent' => $parent,
            'parent_class' => $this->_parentClass,
            'tag_action' => $tagAction,
        ];

        $templateForm->paramsForInputTemplate = $inputParams;
    }

    /**
     * Добавление изображения, отправленного из отзыва.
     *
     * @param FieldAggregate $photoGallery
     *
     * @return bool
     */
    private function addImage(FieldAggregate $photoGallery)
    {
        if (isset($_FILES['photo_gallery'])) {
            $photoGallery->value = $_FILES['photo_gallery']['name'];

            if (ArrayHelper::getValue($_FILES, 'photo_gallery.tmp_name', '')) {
                if (!(
                    isset($_FILES['photo_gallery']['type'])
                    && mb_substr_count($_FILES['photo_gallery']['type'], 'image')
                )) {
                    $photoGallery->addError(
                        'photo_gallery',
                        \Yii::t('review', 'error_send_gallery')
                    );

                    return false;
                }

                $aImageInfo = getimagesize($_FILES['photo_gallery']['tmp_name']);
                $sTypeImage = $aImageInfo[2];
                $aAllowImageTypes = array_keys(Image::getAllowImageTypes());

                if (in_array($sTypeImage, $aAllowImageTypes)) {
                    $sContent = file_get_contents($_FILES['photo_gallery']['tmp_name']);

                    GalleryApi::createTempDir();
                    $allPath = ROOTPATH . 'web/' . GalleryApi::$sTempPath . $photoGallery->value;

                    file_put_contents($allPath, $sContent);

                    try {
                        //добавление альбома
                        $idAlbum = Album::setAlbum([
                            'owner' => 'section',
                            // владелец
                            'section_id' => $this->_idSection,
                            // родительский раздел
                            'profile_id' => Profile::getDefaultId(Profile::TYPE_REVIEWS),
                            // Профиль форматов
                        ]);

                        $idProfile = Profile::getDefaultId(Profile::TYPE_REVIEWS);

                        $aCrop = Format::getCropTypeProfile(Profile::TYPE_REVIEWS);

                        Photo::addPhotoInAlbum(
                            $allPath,
                            $idAlbum,
                            $aCrop,
                            $idProfile
                        );
                    } catch (\Exception $e) {
                        $photoGallery->addError(
                            'photo_gallery',
                            $e->getMessage()
                        );

                        return false;
                    }

                    Files::remove($allPath);
                    //изменение данных о загруженной фотографии
                    $this->_documents->photo_gallery = $idAlbum;

                    return true;
                }
                $photoGallery->addError(
                    'photo_gallery',
                    \Yii::t('review', 'error_send_file')
                    );

                return false;
            }
        }

        return true;
    }

    public function getLinkAutoReply(): string
    {
        return Site::admUrl('Review');
    }
}
