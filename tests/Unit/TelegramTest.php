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
    * setUp
    */
    protected function setUp()
    {
        $this->telegram = new Telegram('testapikey', 'testbotname');
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
