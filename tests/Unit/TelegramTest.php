<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

use Longman\TelegramBot\Telegram;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class TelegramTest extends TestCase
{
    /**
    * @var \Longman\TelegramBot\Telegram
    */
    private $telegram;

    /**
     * @var array
     */
    private $custom_commands_paths = [
        '/tmp/php-telegram-bot-custom-commands-1',
        '/tmp/php-telegram-bot-custom-commands-2',
        '/tmp/php-telegram-bot-custom-commands-3',
    ];

    /**
    * setUp
    */
    protected function setUp()
    {
        $this->telegram = new Telegram('testapikey', 'testbotname');

        // Create a few custom commands paths.
        foreach ($this->custom_commands_paths as $custom_path) {
            mkdir($custom_path);
        }
    }

    /**
     * tearDown
     */
    protected function tearDown()
    {
        // Clean up the custom commands paths.
        foreach ($this->custom_commands_paths as $custom_path) {
            rmdir($custom_path);
        }
    }

    /**
     * @test
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     */
    public function newInstanceWithoutApiKeyParam()
    {
        new Telegram(null, 'testbotname');
    }

    /**
     * @test
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     */
    public function newInstanceWithoutBotNameParam()
    {
        new Telegram('testapikey', null);
    }

    /**
     * @test
     */
    public function getApiKey()
    {
        $this->assertEquals('testapikey', $this->telegram->getApiKey());
    }

    /**
     * @test
     */
    public function getBotName()
    {
        $this->assertEquals('testbotname', $this->telegram->getBotName());
    }

    /**
     * @test
     */
    public function enableAdmins()
    {
        $tg = &$this->telegram;

        $this->assertEmpty($tg->getAdminList());

        $tg->enableAdmin(1);
        $this->assertCount(1, $tg->getAdminList());

        $tg->enableAdmins([2, 3]);
        $this->assertCount(3, $tg->getAdminList());

        $tg->enableAdmin(2);
        $this->assertCount(3, $tg->getAdminList());

        $tg->enableAdmin('a string?');
        $this->assertCount(3, $tg->getAdminList());
    }

    /**
     * @test
     */
    public function addCustomCommandsPaths()
    {
        $tg = &$this->telegram;

        $this->assertAttributeCount(1, 'commands_paths', $tg);

        $tg->addCommandsPath($this->custom_commands_paths[0]);
        $this->assertAttributeCount(2, 'commands_paths', $tg);

        $tg->addCommandsPath('/invalid/path');
        $this->assertAttributeCount(2, 'commands_paths', $tg);

        $tg->addCommandsPaths([
            $this->custom_commands_paths[1],
            $this->custom_commands_paths[2],
        ]);
        $this->assertAttributeCount(4, 'commands_paths', $tg);

        $tg->addCommandsPath($this->custom_commands_paths[0]);
        $this->assertAttributeCount(4, 'commands_paths', $tg);
    }

    /**
     * @test
     */
    public function getCommandsList()
    {
        $commands = $this->telegram->getCommandsList();
        $this->assertInternalType('array', $commands);
        $this->assertNotCount(0, $commands);
    }

    /**
     * @test
     */
    public function getHelpCommandObject()
    {
        $command = $this->telegram->getCommandObject('help');
        $this->assertInstanceOf('Longman\TelegramBot\Commands\UserCommands\HelpCommand', $command);
    }
}
