<?php

namespace unit\base\command;

/*
 * Тест прототипа "Команда" (Command)
 * Generated by PHPUnit_SkeletonGenerator on 2013-01-15 at 12:59:13.
 */

use skewer\base\command as Commands;

class HubTest extends \Codeception\Test\Unit
{
    /**
     * @var \skewer\base\command\Hub
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Commands\Hub();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * Проверка тестовой команды.
     *
     * @covers \skewer\base\command\Hub::init
     * @covers \skewer\base\command\Hub::addCommand
     * @covers \skewer\base\command\Hub::execute
     * @covers \skewer\base\command\Hub::rollback
     */
    public function testTestCommand()
    {
        $oHub = new TestHub();
        $oIncCommand = new TestCommand();
        $oHub->addCommand($oIncCommand);

        $this->assertTrue($oIncCommand->isInited());
        $this->assertFalse($oIncCommand->isExecuted());
        $this->assertFalse($oIncCommand->isRolledBack());

        $oHub->execute();

        $this->assertTrue($oHub->isInited());
        $this->assertTrue($oIncCommand->isExecuted());
        $this->assertFalse($oIncCommand->isRolledBack());

        $oHub->rollback();

        $this->assertTrue($oIncCommand->isExecuted());
        $this->assertTrue($oIncCommand->isRolledBack());
    }

    /**
     * Проверка тестовой команды с ошибкой.
     *
     * @covers \skewer\base\command\Hub::execute
     */
    public function testFailCommand()
    {
        $this->expectException(\Exception::class);

        $oFailCommand = new TestCommandFailOnExecute();
        $oFailCommand->execute();
    }

    /**
     * Тестирование добавления набора команд и подчиненного концентратора.
     *
     * @covers \skewer\base\command\Hub::addCommand
     * @covers \skewer\base\command\Hub::addCommandList
     * @covers \skewer\base\command\Hub::execute
     * @covers \skewer\base\command\Hub::rollback
     */
    public function testExecute()
    {
        $oHub = new TestHub();
        $oCommand1 = new TestCommand();
        $oCommand2 = new TestCommand();
        $oCommand3 = new TestCommand();
        $oSubHub = new TestHub();
        $oCommand4 = new TestCommand();
        $oCommand5 = new TestCommand();
        $oSubHub->addCommandList([
            $oCommand4,
            $oCommand5,
        ]);

        $oHub->addCommand($oCommand1);
        $oHub->addCommandList([
            $oCommand2,
            $oSubHub,
            $oCommand3,
        ]);

        $oHub->execute();

        $this->assertTrue($oHub->isInited(), 'Основной концентратор не инициализирован');
        $this->assertTrue($oSubHub->isInited(), 'Подчиненный концентратор не инициализирован');

        $bAllExecuted = $oCommand1->isExecuted()
            and $oCommand2->isExecuted()
            and $oCommand3->isExecuted()
            and $oCommand4->isExecuted()
            and $oCommand5->isExecuted();
        $this->assertTrue(
            $bAllExecuted,
            'не сработала команда добавления '
        );

        $oHub->rollback();

        $bAllRolledBack =
            $oCommand1->isRolledBack()
            and $oCommand2->isRolledBack()
            and $oCommand3->isRolledBack()
            and $oCommand4->isRolledBack()
            and $oCommand5->isRolledBack();
        $this->assertTrue(
            $bAllRolledBack,
            'не сработал откат команд'
        );
    }

    /**
     * Тестирование отката значений в случает отказа одной из команд.
     *
     * @covers \skewer\base\command\Hub::execute
     * @covers \skewer\base\command\Hub::rollback
     * @covers \skewer\base\command\Hub::getError
     */
    public function testRollback()
    {
        $oHub = new TestHub();
        $oCommand1 = new TestCommand();
        $oCommand2 = new TestCommand();
        $oCommandFail3 = new TestCommandFailOnExecute();
        $oCommand4 = new TestCommand();
        $oCommand5 = new TestCommand();
        $oSubHub = new TestHub();
        $oSubHub->addCommandList([
            $oCommand2,
            $oCommandFail3,
            $oCommand4,
        ]);

        $oHub->addCommandList([
            $oCommand1,
            $oSubHub,
            $oCommand5,
        ]);

        $oHub->execute();

        $bRightExecuted = $oCommand1->isExecuted()
            && $oCommand2->isExecuted()
            && !$oCommandFail3->isExecuted() // не должен быть выполнен
            && !$oCommand4->isExecuted()
            && !$oCommand5->isExecuted();
        $this->assertTrue(
            $bRightExecuted,
            'не правильно сработала остановка после ошибки'
        );

        $bRightRolledBack = $oCommand1->isRolledBack()
            && $oCommand2->isRolledBack()
            && !$oCommandFail3->isRolledBack() // не должен быть откачен
            && !$oCommand4->isRolledBack()
            && !$oCommand5->isRolledBack();
        $this->assertTrue(
            $bRightRolledBack,
            'не правильно сработал откат после ошибки'
        );

        $this->assertEquals(
            TestCommandFailOnExecute::FAIL_TEXT,
            $oHub->getError()->getMessage(),
            'текст ошибки не получен'
        );
    }

    /**
     * Проверка ошибки при двойном добавлении.
     *
     * @covers \skewer\base\command\Hub::addCommandList
     */
    public function testDoubleAddError()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('double');

        $oHub = new TestHub();
        $oCommand = new TestCommand();
        $oHub->addCommandList([
            $oCommand,
            $oCommand,
        ]);
    }

    /**
     * Проверка ошибки при двойном добавлении.
     *
     * @covers \skewer\base\command\Hub::execute
     */
    public function testDoubleExecuteError()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('execute');

        $oHub = new TestHub();
        $oHub->addCommandList([
            new TestCommand(),
        ]);
        $oHub->execute();
        $oHub->execute();
    }

    /**
     * Проверка выброса исключение при ошибке в откате.
     *
     * @covers \skewer\base\command\Hub::execute
     * @covers \skewer\base\command\Hub::rollback
     */
    public function testFailRollback()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('rollback');

        $oHub = new TestHub();
        $oHub->addCommandList([
            new TestCommand(),
            new TestCommandFailOnRollback(), // баг в откате
            new TestCommandFailOnExecute(),  // баг в исполнении
            new TestCommand(),
        ]);

        $oHub->execute();
    }

    /** @var bool флаг наличия прохода */
    protected $bPassed = false;

    public function onPass()
    {
        $this->bPassed = true;
    }

    /**
     * Проверка уведомлений.
     *
     * @covers \skewer\base\command\Action::setHub
     * @covers \skewer\base\command\Action::getHub
     * @covers \skewer\base\command\Action::listenTo
     * @covers \skewer\base\command\Action::notify
     * @covers \skewer\base\command\Hub::notify
     * @covers \skewer\base\command\Hub::addListener
     */
    public function testNotification()
    {
        $oHub = new TestHub();
        $oGetter = new TestNotifyGetterCommand();
        $oSender = new TestNotifySenderCommand();

        $oHub->addCommandList([
            $oGetter,
            $oSender,
        ]);

        $oHub->addListener(TestNotifySenderCommand::EVENT_ID, [$this, 'onPass']);

        $this->assertTrue($oHub->execute(), 'набор команд не выполнен');

        $this->assertTrue($this->bPassed, 'не выполнен вызов кодписанной на событие фенкции');

        $this->assertEquals(
            TestNotifySenderCommand::EVENT_VAL,
            $oGetter->getVal(),
            'не работает рассылка уведомлений'
        );
    }

    /**
     * Проверка уведомлений
     * Отсылка из подчиненного наверх с инициализацией ДО.
     *
     * @covers \skewer\base\command\Hub::notify
     * @covers \skewer\base\command\Hub::hasParentHub
     * @covers \skewer\base\command\Hub::fireEvent
     */
    public function testNotificationWithLevelsUpBefore()
    {
        $oHub = new TestHub();
        $oSubHub = new TestHub();
        $oGetter = new TestNotifyGetterCommand();
        $oSender = new TestNotifySenderCommand();

        $oSubHub->addCommand($oGetter);
        $oHub->addCommandList([
            $oSubHub,
            $oSender,
        ]);

        $oHub->execute();

        $this->assertEquals(
            TestNotifySenderCommand::EVENT_VAL,
            $oGetter->getVal(),
            'не работает рассылка уведомлений в подчиненном хабе при ' .
                'добавлении подписки ДО добавления хаба'
        );
    }

    /**
     * Проверка уведомлений
     * Отсылка из подчиненного наверх с инициализацией ПОСЛЕ.
     *
     * @covers \skewer\base\command\Hub::notify
     * @covers \skewer\base\command\Hub::hasParentHub
     * @covers \skewer\base\command\Hub::fireEvent
     */
    public function testNotificationWithLevelsUpAfter()
    {
        $oHub = new TestHub();
        $oSubHub = new TestHub();
        $oGetter = new TestNotifyGetterCommand();
        $oSender = new TestNotifySenderCommand();

        $oHub->addCommandList([
            $oSender,
            $oSubHub,
        ]);
        $oSubHub->addCommand($oGetter);

        $oHub->execute();

        $this->assertEquals(
            TestNotifySenderCommand::EVENT_VAL,
            $oGetter->getVal(),
            'не работает рассылка уведомлений в подчиненном хабе при ' .
                'добавлении подписки ПОСЛЕ добавления хаба'
        );
    }

    /**
     * Проверка уведомлений
     * Отсылка в подчиненный рядом с инициализацией ДО.
     *
     * @covers \skewer\base\command\Hub::notify
     * @covers \skewer\base\command\Hub::getHub
     * @covers \skewer\base\command\Hub::hasParentHub
     * @covers \skewer\base\command\Hub::fireEvent
     */
    public function testNotificationWithLevelsDownBefore()
    {
        $oHub = new TestHub();
        $oSubHub = new TestHub();
        $oGetter = new TestNotifyGetterCommand();
        $oSender = new TestNotifySenderCommand();

        $oSubHub->addCommand($oSender);
        $oHub->addCommandList([
            $oGetter,
            $oSubHub,
        ]);

        $oHub->execute();

        $this->assertEquals(
            TestNotifySenderCommand::EVENT_VAL,
            $oGetter->getVal(),
            'не работает рассылка уведомлений в подчиненном хабе при ' .
                'добавлении подписки ДО добавления хаба'
        );
    }

    /**
     * Проверка уведомлений
     * Отсылка в подчиненный рядом с инициализацией ПОСЛЕ.
     *
     * @covers \skewer\base\command\Hub::notify
     * @covers \skewer\base\command\Hub::getHub
     * @covers \skewer\base\command\Hub::hasParentHub
     * @covers \skewer\base\command\Hub::fireEvent
     */
    public function testNotificationWithLevelsDownAfter()
    {
        $oHub = new TestHub();
        $oSubHub = new TestHub();
        $oGetter = new TestNotifyGetterCommand();
        $oSender = new TestNotifySenderCommand();

        $oHub->addCommandList([
            $oGetter,
            $oSubHub,
        ]);
        $oSubHub->addCommand($oSender);

        $oHub->execute();

        $this->assertEquals(
            TestNotifySenderCommand::EVENT_VAL,
            $oGetter->getVal(),
            'не работает рассылка уведомлений в подчиненном хабе при ' .
                'добавлении подписки ПОСЛЕ добавления хаба'
        );
    }
}

/**
 * Тестовая комманда, организующая увеличение значения переменной.
 */
class TestCommand extends Commands\Action
{
    /** @var bool флаг выполнения */
    protected $bExecuted = false;

    /** @var bool флаг отката */
    protected $bRolledBack = false;

    /**
     * Инициализация
     * Добавление слушателей событий.
     */
    protected function init()
    {
    }

    /**
     * Отдает статус инициализации.
     *
     * @return bool
     */
    public function isInited()
    {
        return $this->bInited;
    }

    /**
     * Выполнение команды.
     *
     * @throws \Exception
     */
    public function execute()
    {
        $this->bExecuted = true;
    }

    /**
     * Откат команды.
     */
    public function rollback()
    {
        $this->bRolledBack = true;
    }

    /**
     * Сообщает было ли исполнение.
     *
     * @return bool
     */
    public function isExecuted()
    {
        return $this->bExecuted;
    }

    /**
     * Сообщает был ли откат
     *
     * @return bool
     */
    public function isRolledBack()
    {
        return $this->bRolledBack;
    }
}

/**
 * Тестовая команда, падающая при выполнении.
 */
class TestCommandFailOnExecute extends TestCommand
{
    const FAIL_TEXT = 'fail command text';

    /**
     * Выполнение команды.
     *
     * @throws \Exception
     */
    public function execute()
    {
        throw new \Exception(self::FAIL_TEXT);
    }
}

/**
 * Тестовая команда, падающая при выполнении.
 */
class TestCommandFailOnRollback extends TestCommand
{
    const ROLLBACK_FAIL_TEXT = 'fail on rollback command text';

    /**
     * Откат команды.
     *
     * @throws \Exception
     */
    public function rollback()
    {
        throw new \Exception(self::ROLLBACK_FAIL_TEXT);
    }
}

/**
 * Тестовый концентратор
 */
class TestHub extends Commands\Hub
{
    /**
     * Отдает статус инициализации.
     *
     * @return bool
     */
    public function isInited()
    {
        return $this->bInited;
    }
}

/**
 * Тестовый класс уведомитель.
 */
class TestNotifySenderCommand extends Commands\Action
{
    const EVENT_ID = 1;

    const EVENT_VAL = 4564654;

    /**
     * Выполнение команды.
     *
     * @throws \Exception
     */
    public function execute()
    {
        $this->notify(self::EVENT_ID, self::EVENT_VAL);
    }

    /**
     * Инициализация
     * Добавление слушателей событий.
     */
    protected function init()
    {
    }

    /**
     * Откат команды.
     */
    public function rollback()
    {
    }
}

/**
 * Тестовый прослушивающий класс
 */
class TestNotifyGetterCommand extends Commands\Action
{
    /** @var int полученное значение */
    protected $iVal;

    /**
     * Инициализация
     * Добавление слушателей событий.
     */
    protected function init()
    {
        $this->listenTo(TestNotifySenderCommand::EVENT_ID, 'onEvent');
    }

    /**
     * Событие.
     *
     * @param int $iVal
     */
    public function onEvent($iVal)
    {
        $this->iVal = $iVal;
    }

    /**
     * Отдает значение.
     *
     * @return mixed
     */
    public function getVal()
    {
        return $this->iVal;
    }

    /**
     * Выполнение команды.
     *
     * @throws \Exception
     */
    public function execute()
    {
    }

    /**
     * Откат команды.
     */
    public function rollback()
    {
    }
}