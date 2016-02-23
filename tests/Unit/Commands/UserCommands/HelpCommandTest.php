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
use Longman\TelegramBot\Commands\UserCommands\HelpCommand;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class HelpCommandTest extends CommandTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->command = new HelpCommand($this->telegram);
    }

    /**
     * @test
     */
    public function testHelpCommandProperties()
    {
        $this->assertAttributeEquals('help', 'name', $this->command);
        $this->assertAttributeEquals('Show bot commands help', 'description', $this->command);
        $this->assertAttributeEquals('/help or /help <command>', 'usage', $this->command);
    }

    /**
     * @test
     */
    public function testHelpCommandExecuteWithoutParameter()
    {
        $text = $this->command
            ->setUpdate(TestHelpers::getFakeUpdateCommandObject('/help'))
            ->execute()
            ->getResult()
            ->getText();
        $this->assertContains(
            "botname v. " . $this->telegram->getVersion() . "\n\nCommands List:",
            $text
        );
    }

    /**
     * @test
     */
    public function testHelpCommandExecuteWithParameterInvalidCommand()
    {
        $text = $this->command
            ->setUpdate(TestHelpers::getFakeUpdateCommandObject('/help invalidcommand'))
            ->execute()
            ->getResult()
            ->getText();
        $this->assertEquals('No help available: Command /invalidcommand not found', $text);
    }

    /**
     * @test
     */
    public function testHelpCommandExecuteWithParameterValidCommand()
    {
        $text = $this->command
            ->setUpdate(TestHelpers::getFakeUpdateCommandObject('/help echo'))
            ->execute()
            ->getResult()
            ->getText();
        $this->assertContains("Description: Show text\nUsage: /echo <text>", $text);
    }
}
