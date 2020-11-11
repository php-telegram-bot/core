<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit\Commands;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Tests\Unit\TestCase;
use Longman\TelegramBot\Tests\Unit\TestHelpers;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @package         TelegramTest
 */
class CommandTest extends TestCase
{
    /**
     * @var string
     */
    private $command_namespace = Command::class;

    /**
     * @var Telegram
     */
    private $telegram;

    /**
     * @var Command
     */
    private $command_stub;

    /**
     * @var Telegram
     */
    private $telegram_with_config;

    /**
     * @var Command
     */
    private $command_stub_with_config;

    public function setUp(): void
    {
        //Default command object
        $this->telegram     = new Telegram(self::$dummy_api_key, 'testbot');
        $this->command_stub = $this->getMockForAbstractClass($this->command_namespace, [$this->telegram]);

        //Create separate command object that contain a command config
        $this->telegram_with_config = new Telegram(self::$dummy_api_key, 'testbot');
        $this->telegram_with_config->setCommandConfig('command_name', ['config_key' => 'config_value']);
        $this->command_stub_with_config = $this->getMockBuilder($this->command_namespace)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        //Set a name for the object property so that the constructor can set the config correctly
        TestHelpers::setObjectProperty($this->command_stub_with_config, 'name', 'command_name');
        $this->command_stub_with_config->__construct($this->telegram_with_config);
    }

    // Test idea from here: http://stackoverflow.com/a/4371606
    public function testCommandConstructorNeedsTelegramObject(): void
    {
        $exception_count = 0;
        $params_to_test  = [
            [],
            [null],
            [12345],
            ['something'],
            [new \stdClass()],
            [$this->telegram], // only this one is valid
        ];

        foreach ($params_to_test as $param) {
            try {
                $this->getMockForAbstractClass($this->command_namespace, $param);
            } catch (\Throwable $e) {
                $exception_count++;
            }
        }

        self::assertEquals(5, $exception_count);
    }

    public function testCommandHasCorrectTelegramObject(): void
    {
        self::assertSame($this->telegram, $this->command_stub->getTelegram());
    }

    public function testDefaultCommandName(): void
    {
        self::assertEmpty($this->command_stub->getName());
    }

    public function testDefaultCommandDescription(): void
    {
        self::assertEquals('Command description', $this->command_stub->getDescription());
    }

    public function testDefaultCommandUsage(): void
    {
        self::assertEquals('Command usage', $this->command_stub->getUsage());
    }

    public function testDefaultCommandVersion(): void
    {
        self::assertEquals('1.0.0', $this->command_stub->getVersion());
    }

    public function testDefaultCommandIsEnabled(): void
    {
        self::assertTrue($this->command_stub->isEnabled());
    }

    public function testDefaultCommandShownInHelp(): void
    {
        self::assertTrue($this->command_stub->showInHelp());
    }

    public function testDefaultCommandNeedsMysql(): void
    {
        self::markTestSkipped('Think about better test');
    }

    public function testDefaultCommandEmptyConfig(): void
    {
        self::assertSame([], $this->command_stub->getConfig());
    }

    public function testDefaultCommandUpdateNull(): void
    {
        self::assertNull($this->command_stub->getUpdate());
    }

    public function testCommandSetUpdateAndMessage(): void
    {
        $stub = $this->command_stub;

        self::assertEquals(null, $stub->getUpdate());
        self::assertEquals(null, $stub->getMessage());

        $update  = TestHelpers::getFakeUpdateObject();
        $message = $update->getMessage();
        $stub->setUpdate($update);
        self::assertEquals($update, $stub->getUpdate());
        self::assertEquals($message, $stub->getMessage());
    }

    public function testCommandWithConfigNotEmptyConfig(): void
    {
        self::assertNotEmpty($this->command_stub_with_config->getConfig());
    }

    public function testCommandWithConfigCorrectConfig(): void
    {
        self::assertEquals(['config_key' => 'config_value'], $this->command_stub_with_config->getConfig());
        self::assertEquals(['config_key' => 'config_value'], $this->command_stub_with_config->getConfig(null));
        self::assertEquals(['config_key' => 'config_value'], $this->command_stub_with_config->getConfig());
        self::assertEquals('config_value', $this->command_stub_with_config->getConfig('config_key'));
        self::assertEquals(null, $this->command_stub_with_config->getConfig('not_config_key'));
    }
}
