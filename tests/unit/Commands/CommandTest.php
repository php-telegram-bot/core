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
    private $command_namespace = 'Longman\TelegramBot\Commands\Command';

    /**
     * @var \Longman\TelegramBot\Telegram
     */
    private $telegram;

    /**
     * @var \Longman\TelegramBot\Commands\Command
     */
    private $command_stub;

    /**
     * @var \Longman\TelegramBot\Telegram
     */
    private $telegram_with_config;

    /**
     * @var \Longman\TelegramBot\Commands\Command
     */
    private $command_stub_with_config;

    public function setUp()
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
            [new \stdClass],
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
        $this->assertAttributeEquals($this->telegram, 'telegram', $this->command_stub);
        $this->assertSame($this->telegram, $this->command_stub->getTelegram());
    }

    public function testDefaultCommandName()
    {
        $this->assertAttributeEquals('', 'name', $this->command_stub);
        $this->assertEmpty($this->command_stub->getName());
    }

    public function testDefaultCommandDescription()
    {
        $this->assertAttributeEquals('Command description', 'description', $this->command_stub);
        $this->assertEquals('Command description', $this->command_stub->getDescription());
    }

    public function testDefaultCommandUsage()
    {
        $this->assertAttributeEquals('Command usage', 'usage', $this->command_stub);
        $this->assertEquals('Command usage', $this->command_stub->getUsage());
    }

    public function testDefaultCommandVersion()
    {
        $this->assertAttributeEquals('1.0.0', 'version', $this->command_stub);
        $this->assertEquals('1.0.0', $this->command_stub->getVersion());
    }

    public function testDefaultCommandIsEnabled()
    {
        $this->assertAttributeEquals(true, 'enabled', $this->command_stub);
        $this->assertTrue($this->command_stub->isEnabled());
    }

    public function testDefaultCommandShownInHelp()
    {
        $this->assertAttributeEquals(true, 'show_in_help', $this->command_stub);
        $this->assertTrue($this->command_stub->showInHelp());
    }

    public function testDefaultCommandNeedsMysql()
    {
        $this->assertAttributeEquals(false, 'need_mysql', $this->command_stub);
    }

    public function testDefaultCommandEmptyConfig()
    {
        $this->assertAttributeEquals([], 'config', $this->command_stub);
    }

    public function testDefaultCommandUpdateNull()
    {
        $this->assertAttributeEquals(null, 'update', $this->command_stub);
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
        $this->assertAttributeEquals($update, 'update', $stub);
        $this->assertEquals($update, $stub->getUpdate());
        $this->assertAttributeEquals($message, 'message', $stub);
        $this->assertEquals($message, $stub->getMessage());
    }

    public function testCommandWithConfigNotEmptyConfig()
    {
        $this->assertAttributeNotEmpty('config', $this->command_stub_with_config);
    }

    public function testCommandWithConfigCorrectConfig()
    {
        $this->assertAttributeEquals(['config_key' => 'config_value'], 'config', $this->command_stub_with_config);
        $this->assertEquals(['config_key' => 'config_value'], $this->command_stub_with_config->getConfig(null));
        $this->assertEquals(['config_key' => 'config_value'], $this->command_stub_with_config->getConfig());
        $this->assertEquals('config_value', $this->command_stub_with_config->getConfig('config_key'));
        $this->assertEquals(null, $this->command_stub_with_config->getConfig('not_config_key'));
    }
}
