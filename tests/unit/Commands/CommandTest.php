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
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
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
    public function testCommandConstructorNeedsTelegramObject()
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
            } catch (\Exception $e) {
                $exception_count++;
            } catch (\Throwable $e) { //For PHP7
                $exception_count++;
            }
        }

        $this->assertEquals(5, $exception_count);
    }

    public function testCommandHasCorrectTelegramObject()
    {
        $this->assertSame($this->telegram, $this->command_stub->getTelegram());
    }

    public function testDefaultCommandName()
    {
        $this->assertEmpty($this->command_stub->getName());
    }

    public function testDefaultCommandDescription()
    {
        $this->assertEquals('Command description', $this->command_stub->getDescription());
    }

    public function testDefaultCommandUsage()
    {
        $this->assertEquals('Command usage', $this->command_stub->getUsage());
    }

    public function testDefaultCommandVersion()
    {
        $this->assertEquals('1.0.0', $this->command_stub->getVersion());
    }

    public function testDefaultCommandIsEnabled()
    {
        $this->assertTrue($this->command_stub->isEnabled());
    }

    public function testDefaultCommandShownInHelp()
    {
        $this->assertTrue($this->command_stub->showInHelp());
    }

    public function testDefaultCommandNeedsMysql()
    {
        $this->markTestSkipped('Think about better test');
        $this->assertAttributeEquals(false, 'need_mysql', $this->command_stub);
    }

    public function testDefaultCommandEmptyConfig()
    {
        $this->assertSame([], $this->command_stub->getConfig());
    }

    public function testDefaultCommandUpdateNull()
    {
        $this->assertNull($this->command_stub->getUpdate());
    }

    public function testCommandSetUpdateAndMessage()
    {
        $stub = $this->command_stub;

        $this->assertSame($stub, $stub->setUpdate());
        $this->assertEquals(null, $stub->getUpdate());
        $this->assertEquals(null, $stub->getMessage());

        $this->assertSame($stub, $stub->setUpdate(null));
        $this->assertEquals(null, $stub->getUpdate());
        $this->assertEquals(null, $stub->getMessage());

        $update  = TestHelpers::getFakeUpdateObject();
        $message = $update->getMessage();
        $stub->setUpdate($update);
        $this->assertEquals($update, $stub->getUpdate());
        $this->assertEquals($message, $stub->getMessage());
    }

    public function testCommandWithConfigNotEmptyConfig()
    {
        $this->assertNotEmpty($this->command_stub_with_config->getConfig());
    }

    public function testCommandWithConfigCorrectConfig()
    {
        $this->assertEquals(['config_key' => 'config_value'], $this->command_stub_with_config->getConfig());
        $this->assertEquals(['config_key' => 'config_value'], $this->command_stub_with_config->getConfig(null));
        $this->assertEquals(['config_key' => 'config_value'], $this->command_stub_with_config->getConfig());
        $this->assertEquals('config_value', $this->command_stub_with_config->getConfig('config_key'));
        $this->assertEquals(null, $this->command_stub_with_config->getConfig('not_config_key'));
    }
}
