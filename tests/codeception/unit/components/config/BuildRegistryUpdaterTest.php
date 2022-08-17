<?php

namespace unit\components\config;

use skewer\components\config\BuildRegistry;
use skewer\components\config\BuildRegistryUpdater;
use skewer\components\config\ModuleConfig;
use skewer\components\config\Vars;
use yii\base\Component;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2013-03-12 at 12:42:37.
 */
class BuildRegistryUpdaterTest extends \Codeception\Test\Unit
{
    /**
     * @var BuildRegistryUpdater объект для отката сделанных изменений
     */
    protected $oConfigBack;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->oConfigBack = new BuildRegistryUpdater();
    }

    protected function tearDown()
    {
        // откат
        $this->oConfigBack->set('a', 1);
        $this->oConfigBack->remove('a');
        $this->oConfigBack->commitChanges();
    }

    /**
     * @covers \skewer\components\config\BuildRegistryUpdater::loadData
     * @covers \skewer\components\config\BuildRegistryUpdater::saveData
     */
    public function testSave()
    {
        $oConfig = new BuildRegistryUpdater();
        $oConfig->set('test_conf_save', 123);
        $oConfig->commitChanges();
        $this->assertFalse($oConfig->hasChanges(), 'не сброшен флаг наличия изменений');

        $oConfig = new BuildRegistryUpdater();
        $this->assertSame(123, $oConfig->get('test_conf_save'));
    }

    public static function goTestHandler()
    {
        return 'goTestBeforeHandler';
    }

    public static function goTestHandler2()
    {
        return 'goTestBeforeHandler2';
    }

    public static function goTestHandler3()
    {
        return 'goTestBeforeHandler3';
    }

    private function getAbstractConfig()
    {
        $aConfig['name'] = 'AbstractModule';
        $aConfig['title'] = 'AbstractModuleTitle';
        $aConfig['version'] = '2.5';
        $aConfig['description'] = 'AbstractModuleDescription';
        $aConfig['revision'] = '0025';
        $aConfig['layer'] = 'Adm';

        /* events */
        $aConfig['events']['goTestAbstractModule1'] = [
            'class' => 'unit\components\config\BuildRegistryUpdaterTest',
            'method' => 'goTestHandler',
        ];

        $aConfig['events'][] = [
            'event' => 'goTestAbstractModule2',
            'eventClass' => Component::className(),
            'class' => 'unit\components\config\BuildRegistryUpdaterTest',
            'method' => 'goTestHandler2',
        ];

        $aConfig['events'][] = [
            'event' => 'goTestAbstractModule2',
            'class' => 'unit\components\config\BuildRegistryUpdaterTest',
            'method' => 'goTestHandler3',
        ];

        /* Функциональные политики */
        $aConfig['policy'][] = [
            'name' => 'allowAll',
            'title' => 'Разрешить все',
            'default' => 0,
        ];

        $aConfig['policy'][] = [
            'name' => 'allowView',
            'title' => 'Разрешить просмотр',
            'default' => 1,
        ];

        return $aConfig;
    }

    /**
     * Тест добавления абстрактного модуля
     * Проверяет формат добавления и попадение значений в нужное место.
     *
     * @covers \skewer\components\config\BuildRegistryUpdater
     */
    public function testAbstractInstall()
    {
        $oModuleConfig = new ModuleConfig($this->getAbstractConfig());

        // объект обновления конфигурации
        $oBuildConfig = new BuildRegistryUpdater();
        $this->assertFalse($oBuildConfig->moduleExists('AbstractModule', 'Adm'));
        $oBuildConfig->registerModule($oModuleConfig);
        $this->assertTrue($oBuildConfig->moduleExists('AbstractModule', 'Adm'));
        $oBuildConfig->commitChanges();
        $this->assertTrue($oBuildConfig->moduleExists('AbstractModule', 'Adm'));

        // объект запроса конфигурации
        $oGetConfig = new BuildRegistry();

        $this->assertSame(
            'AbstractModule',
            $oBuildConfig->get('layers.Adm.modules.AbstractModule.name'),
            'не правильно добавлен модуль'
        );
        $this->assertSame(
            'AbstractModule',
            $oGetConfig->get('layers.Adm.modules.AbstractModule.name'),
            'запросник: не правильно добавлен модуль'
        );

        $this->assertSame(
            'goTestHandler',
            $oBuildConfig->get('events.goTestAbstractModule1.0.method'),
            'не добавлено событие'
        );
        $this->assertSame(
            'goTestHandler',
            $oGetConfig->get('events.goTestAbstractModule1.0.method'),
            'запросник: не добавлено событие'
        );

        $this->assertSame(
            'goTestHandler2',
            $oBuildConfig->get('events.goTestAbstractModule2.0.method'),
            'не добавлено 2 событие'
        );
        $this->assertSame(
            'goTestHandler2',
            $oGetConfig->get('events.goTestAbstractModule2.0.method'),
            'запросник: не добавлено 2 событие'
        );

        $this->assertSame(
            'goTestHandler3',
            $oBuildConfig->get('events.goTestAbstractModule2.1.method'),
            'не добавлено 3 событие'
        );
        $this->assertSame(
            'goTestHandler3',
            $oGetConfig->get('events.goTestAbstractModule2.1.method'),
            'запросник: не 3 добавлено событие'
        );

        $this->assertSame(
            'allowAll',
            $oBuildConfig->get('policy.Adm.AbstractModule.items.0.name'),
            'не добавлены функциональные политики'
        );
        $this->assertSame(
            'allowAll',
            $oGetConfig->get('policy.Adm.AbstractModule.items.0.name'),
            'запросник: не добавлены функциональные политики'
        );

        $this->assertNull(
            $oGetConfig->get('layers.Adm.modules.AbstractModule.description'),
            'Модуль содержит лишний параметр: description'
        );

        /*
         * Удаление
         */
        $this->assertTrue(
            $oBuildConfig->removeModule('AbstractModule', 'Adm'),
            'неверный ответ при удалении'
        );
        $oBuildConfig->commitChanges();

        $oGetConfig = new BuildRegistry();

        $this->assertNull(
            $oBuildConfig->get('layers.Adm.modules.AbstractModule.filePath'),
            'не удален модуль'
        );
        $this->assertNull(
            $oGetConfig->get('layers.Adm.modules.AbstractModule.filePath'),
            'запросник: не удален модуль'
        );

        $this->assertNull(
            $oBuildConfig->get('events.goTestAbstractModule1.0.method'),
            'не удалено событие'
        );
        $this->assertNull(
            $oGetConfig->get('events.goTestAbstractModule1.0.method'),
            'запросник: не удалено событие'
        );

        $this->assertNull(
            $oBuildConfig->get('events.goTestAbstractModule2.0.method'),
            'не удалено 2 событие'
        );
        $this->assertNull(
            $oGetConfig->get('events.goTestAbstractModule2.0.method'),
            'запросник: не удалено 2 событие'
        );

        $this->assertNull(
            $oBuildConfig->get('events.goTestAbstractModule2.1.method'),
            'не удалено 3 событие'
        );
        $this->assertNull(
            $oGetConfig->get('events.goTestAbstractModule2.1.method'),
            'запросник: не удалено 3 событие'
        );

        $this->assertNull(
            $oBuildConfig->get('policy.Adm.AbstractModule.items.0.name'),
            'не удалены функциональные политики'
        );
        $this->assertNull(
            $oGetConfig->get('policy.Adm.AbstractModule.items.0.name'),
            'запросник: не удалены функциональные политики'
        );

        $this->assertNull(
            $oBuildConfig->get('policy.AbstractModule.items.0.name'),
            'не удален функциональные политики'
        );
        $this->assertNull(
            $oGetConfig->get('policy.AbstractModule.items.0.name'),
            'запросник: не удален функциональные политики'
        );
    }

    /**
     * Тест добавления абстрактного модуля в слой Tool
     * Проверяет формат добавления и попадение значений в нужное место.
     *
     * @covers \skewer\components\config\BuildRegistryUpdater
     */
    public function testAbstractInstallTool()
    {
        $aConfig = [];
        $aConfig['name'] = 'AbstractModule';
        $aConfig['title'] = 'AbstractModuleTitle';
        $aConfig['version'] = '2.5';
        $aConfig['description'] = 'AbstractModuleDescription';
        $aConfig['revision'] = '0025';
        $aConfig['layer'] = 'Tool';

        $oModuleConfig = new ModuleConfig($aConfig);

        // объект обновления конфигурации
        $oBuildConfig = new BuildRegistryUpdater();
        $this->assertFalse($oBuildConfig->moduleExists('AbstractModule', 'Tool'));
        $oBuildConfig->registerModule($oModuleConfig);
        $this->assertTrue($oBuildConfig->moduleExists('AbstractModule', 'Tool'));
        $oBuildConfig->commitChanges();
        $this->assertTrue($oBuildConfig->moduleExists('AbstractModule', 'Tool'));

        // объект запроса конфигурации
        $oGetConfig = new BuildRegistry();

        $this->assertSame(
            'AbstractModule',
            $oBuildConfig->get('layers.Tool.modules.AbstractModule.name'),
            'не правильно добавлен модуль'
        );
        $this->assertSame(
            'AbstractModule',
            $oGetConfig->get('layers.Tool.modules.AbstractModule.name'),
            'запросник: не правильно добавлен модуль'
        );

        /*
         * Удаление
         */
        $this->assertTrue(
            $oBuildConfig->removeModule('AbstractModule', 'Tool'),
            'неверный ответ при удалении'
        );
        $oBuildConfig->commitChanges();

        $oGetConfig = new BuildRegistry();

        $this->assertNull(
            $oBuildConfig->get('layers.Tool.modules.AbstractModule.filePath'),
            'не удален модуль'
        );
        $this->assertNull(
            $oGetConfig->get('layers.Tool.modules.AbstractModule.filePath'),
            'запросник: не удален модуль'
        );
    }

    /**
     * Тест добавления абстрактного модуля c проверкой на основе геттеров.
     *
     * @covers \skewer\components\config\BuildRegistryUpdater::loadData
     * @covers \skewer\components\config\BuildRegistry::getAllEvents
     */
    public function testAbstractInstallGetters()
    {
        $oNewModuleConfig = new ModuleConfig($this->getAbstractConfig());

        // объект обновления конфигурации
        $oBuildConfig = new BuildRegistryUpdater();
        $oBuildConfig->registerModule($oNewModuleConfig);
        $oBuildConfig->commitChanges();

        $oBuildRegistry = new BuildRegistry();

        $oModuleConfig = $oBuildRegistry->getModuleConfig('AbstractModule', 'Adm');

        $this->assertSame(
            'AbstractModule',
            $oModuleConfig->getName(),
            'не правильно добавлен модуль'
        );

        $this->assertArrayHasKey(
            0,
            $oBuildRegistry->getEvents('goTestAbstractModule1'),
            'не найден 1 метод в событиях'
        );

        $this->assertArrayHasKey(
            0,
            $oBuildRegistry->getEvents('goTestAbstractModule2'),
            'не найден 2 метод в событиях'
        );

        $this->assertArrayHasKey(
            1,
            $oBuildRegistry->getEvents('goTestAbstractModule2'),
            'не найден 3 метод в событиях'
        );

        $this->assertNotEmpty(
            $oBuildRegistry->getAllEvents(),
            'Событий не найдено'
        );

        $aPolicyItems = $oBuildRegistry->getFuncPolicyItems('AbstractModule', 'Adm');
        $bFound = false;
        foreach ($aPolicyItems as $aPolicy) {
            if ($aPolicy[Vars::POLICY_VAL_NAME] === 'allowAll') {
                $bFound = true;
                $this->assertSame(
                    [
                        'name' => 'allowAll',
                        'title' => 'Разрешить все',
                        'default' => 0,
                    ],
                    $aPolicy,
                    'неверный формат функциональной политики'
                );
            }
        }
        $this->assertTrue(
            $bFound,
            'не найдена функциональная политика'
        );
    }

    /**
     * Тест запроса модуля.
     *
     * @covers \skewer\components\config\BuildRegistryUpdater::moduleExists
     */
    public function testGetModuleConfig()
    {
        $oConfig = new BuildRegistryUpdater();

        $this->assertFalse(
            $oConfig->moduleExists('News56789', 'Page'),
            'есть модуль с левым названием'
        );

        $this->assertTrue(
            $oConfig->moduleExists('News', 'Page'),
            'нет модуля [News]'
        );
    }

    /**
     * Тест добавления абстрактного модуля c проверкой на основе геттеров.
     *
     * @covers \skewer\components\config\BuildRegistryUpdater::registerModule
     */
    public function testDoubleRegister()
    {
        $oNewModuleConfig = new ModuleConfig($this->getAbstractConfig());

        // объект обновления конфигурации
        $oBuildConfig = new BuildRegistryUpdater();
        $oBuildConfig->registerModule($oNewModuleConfig);

        $this->expectException('skewer\components\config\Exception', 'exists');

        $oBuildConfig->registerModule($oNewModuleConfig);
    }
}
