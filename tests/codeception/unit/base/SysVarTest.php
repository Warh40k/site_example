<?php

namespace unit\base;

use skewer\base\SysVar;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-10-08 at 15:07:17.
 */
class SysVarTest extends \Codeception\Test\Unit
{
    /**
     * @var SysVar
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SysVar();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \skewer\base\SysVar::get()
     */
    public function testGet()
    {
        $this->assertNull(SysVar::get(''), 'Неверный результат для пустого значения!');

        $this->assertNull(SysVar::get(5), 'Неверный результат для некорректных входных параметров. Type:int');

        $this->assertNull(SysVar::get(true), 'Неверный результат для некорректных входных параметров. Type:boolean');

        $this->assertNull(SysVar::get(false), 'Неверный результат для некорректных входных параметров. Type:boolean');

        $this->assertNull(SysVar::get([]), 'Неверный результат для некорректных входных параметров. Type:array');
        $this->assertNull(SysVar::get([1]), 'Неверный результат для некорректных входных параметров. Type:array');
        $this->assertNull(SysVar::get([1 => 3, 5 => 6]), 'Неверный результат для некорректных входных параметров. Type:array');
        $this->assertNull(SysVar::get(['key' => 'value']), 'Неверный результат для некорректных входных параметров. Type:array');

        $this->assertNull(SysVar::get(new SysVar()), 'Неверный результат для некорректных входных параметров. Type:Object');

        $this->assertNull(SysVar::get('заведомо неизвестного значение'), 'Неверный результат для заведомо неизвестного значения!');

        $sVarValue = 'test1';
        $sVarKey = 'test2';

        SysVar::set($sVarKey, $sVarValue);
        $this->assertSame($sVarValue, SysVar::get($sVarKey), 'Неверный результат!');
    }

    /**
     * @covers \skewer\base\SysVar::getSafe
     */
    public function testGetSafe()
    {
        $sVarValue = 'test_1';
        $sVarKey = 'test_2';
        $sVarKey2 = 'test_4';
        $sDefault = 'test_3';
        $sDefault2 = 'test_5';

        $this->assertNull(SysVar::getSafe('', $sDefault), 'Неверный результат для пустого значения!');
        $this->assertSame($sDefault, SysVar::getSafe($sVarKey, $sDefault), 'Неверный результат!');
        SysVar::set($sVarKey2, $sVarValue);
        $this->assertSame($sVarValue, SysVar::getSafe($sVarKey2, $sDefault2), 'Неверный результат!');
    }

    /**
     * @covers \skewer\base\SysVar::set
     */
    public function testSet()
    {
        $sVarKey = 'testKey';
        $sVarValue = 'testValue';
        $sVarKey1 = 'testKey1';
        $sVarValue1 = 'testValue1';
        $sVarValue2 = 'testValue2';
        $sVarKey3 = 'testKey3';
        $sVarValue3 = 'testValue3';
        $this->assertTrue(SysVar::set($sVarKey, $sVarValue), 'Значение задать не удалось');
        $this->assertInternalType('boolean', SysVar::set($sVarKey3, $sVarValue3), 'Метод вернул неверный тип!');

        SysVar::set($sVarKey1, $sVarValue1);
        $this->assertSame($sVarValue1, SysVar::get($sVarKey1), 'Параметр записан неверно!');
        $this->assertSame($sVarValue1, SysVar::getSafe($sVarKey1), 'Параметр записан неверно!');
        $this->assertSame($sVarValue1, SysVar::getSafe($sVarKey1, 'test'), 'Параметр записан неверно!');

        SysVar::set($sVarKey1, $sVarValue2);
        $this->assertSame($sVarValue2, SysVar::get($sVarKey1), 'Параметр записан неверно!');
        $this->assertSame($sVarValue2, SysVar::getSafe($sVarKey1), 'Параметр записан неверно!');
        $this->assertSame($sVarValue2, SysVar::getSafe($sVarKey1, 'test'), 'Параметр записан неверно!');
    }

    /**
     * @covers \skewer\base\SysVar::set
     */
    public function testSetEmpty()
    {
        $this->assertTrue(!SysVar::set('', 'test'), 'Сохранен параметр с пустым ключом!');
    }

    /**
     * @covers \skewer\base\SysVar::del
     */
    public function testDel()
    {
        $sName = 'test_del_param_789456123';

        $this->assertNull(SysVar::get($sName, null), 'параметр уже есть');

        SysVar::set($sName, 123);

        $this->assertSame(123, SysVar::get($sName), 'парметр не задан');

        $this->assertTrue(SysVar::del($sName), 'неверный ответ при удалении');

        $this->assertNull(SysVar::get($sName, null), 'параметр не удален');

        $this->assertFalse(SysVar::del($sName), 'неверный ответ при повторном удалении');
    }
}
