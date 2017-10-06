<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class MessageTest extends TestCase
{
    public function testTextAndCommandRecognise()
    {
        // /command
        $message = TestHelpers::getFakeMessageObject(['text' => '/help']);
        self::assertEquals('/help', $message->getFullCommand());
        self::assertEquals('help', $message->getCommand());
        self::assertEquals('/help', $message->getText());
        self::assertEquals('', $message->getText(true));

        // text
        $message = TestHelpers::getFakeMessageObject(['text' => 'some text']);
        self::assertNull($message->getFullCommand());
        self::assertNull($message->getCommand());
        self::assertEquals('some text', $message->getText());
        self::assertEquals('some text', $message->getText(true));

        // /command@bot
        $message = TestHelpers::getFakeMessageObject(['text' => '/help@testbot']);
        self::assertEquals('/help@testbot', $message->getFullCommand());
        self::assertEquals('help', $message->getCommand());
        self::assertEquals('/help@testbot', $message->getText());
        self::assertEquals('', $message->getText(true));

        // /commmad text
        $message = TestHelpers::getFakeMessageObject(['text' => '/help some text']);
        self::assertEquals('/help', $message->getFullCommand());
        self::assertEquals('help', $message->getCommand());
        self::assertEquals('/help some text', $message->getText());
        self::assertEquals('some text', $message->getText(true));

        // /command@bot some text
        $message = TestHelpers::getFakeMessageObject(['text' => '/help@testbot some text']);
        self::assertEquals('/help@testbot', $message->getFullCommand());
        self::assertEquals('help', $message->getCommand());
        self::assertEquals('/help@testbot some text', $message->getText());
        self::assertEquals('some text', $message->getText(true));

        // /commmad\n text
        $message = TestHelpers::getFakeMessageObject(['text' => "/help\n some text"]);
        self::assertEquals('/help', $message->getFullCommand());
        self::assertEquals('help', $message->getCommand());
        self::assertEquals("/help\n some text", $message->getText());
        self::assertEquals(' some text', $message->getText(true));

        // /command@bot\nsome text
        $message = TestHelpers::getFakeMessageObject(['text' => "/help@testbot\nsome text"]);
        self::assertEquals('/help@testbot', $message->getFullCommand());
        self::assertEquals('help', $message->getCommand());
        self::assertEquals("/help@testbot\nsome text", $message->getText());
        self::assertEquals('some text', $message->getText(true));

        // /command@bot \nsome text
        $message = TestHelpers::getFakeMessageObject(['text' => "/help@testbot \nsome text"]);
        self::assertEquals('/help@testbot', $message->getFullCommand());
        self::assertEquals('help', $message->getCommand());
        self::assertEquals("/help@testbot \nsome text", $message->getText());
        self::assertEquals("\nsome text", $message->getText(true));
    }

    public function testGetType()
    {
        $message = TestHelpers::getFakeMessageObject(['text' => null]);
        self::assertSame('message', $message->getType());

        $message = TestHelpers::getFakeMessageObject(['text' => '/help']);
        self::assertSame('command', $message->getType());

        $message = TestHelpers::getFakeMessageObject(['text' => 'some text']);
        self::assertSame('text', $message->getType());
    }
}
