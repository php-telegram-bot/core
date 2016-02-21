<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Commands\UserCommands;

use Tests\Unit\Commands\CommandTestCase;
use Tests\TestHelpers;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Commands\UserCommands\EchoCommand;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class EchoCommandTest extends CommandTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->command = new EchoCommand($this->telegram);
    }

    /**
     * @test
     */
    public function testEchoCommandProperties()
    {
        $this->assertAttributeEquals('echo', 'name', $this->command);
        $this->assertAttributeEquals('Show text', 'description', $this->command);
        $this->assertAttributeEquals('/echo <text>', 'usage', $this->command);
    }

    /**
     * @test
     */
    public function testEchoCommandExecuteWithoutParameter()
    {
        $text = $this->command
            ->setUpdate(TestHelpers::getFakeUpdateCommandObject('/echo'))
            ->execute()
            ->getResult()
            ->getText();
        $this->assertEquals('Command usage: /echo <text>', $text);

        $text = $this->command
            ->setUpdate(TestHelpers::getFakeUpdateCommandObject('/echo  '))
            ->execute()
            ->getResult()
            ->getText();
        $this->assertEquals('Command usage: /echo <text>', $text);
    }

    /**
     * @test
     */
    public function testEchoCommandExecuteWithParameter()
    {
        $text = $this->command
            ->setUpdate(TestHelpers::getFakeUpdateCommandObject('/echo Message!'))
            ->execute()
            ->getResult()
            ->getText();
        $this->assertEquals('Message!', $text);
    }
}
