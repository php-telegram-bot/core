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

use Tests\TestHelpers;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class MessageTest extends TestCase
{
    /**
     * @var \Longman\TelegramBot\Entities\Message
     */
    private $message;

    /**
     * setUp
     */
    protected function setUp()
    {
    }

    public function testTextAndCommandRecognise() {
        // /command
        $this->message = TestHelpers::getFakeMessageObject(['text' => '/help']);
        $this->assertEquals('/help', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals('/help', $this->message->getText());
        $this->assertEquals('', $this->message->getText(true));

        // text
        $this->message = TestHelpers::getFakeMessageObject(['text' => 'some text']);
        $this->assertEquals('', $this->message->getFullCommand());
        $this->assertEquals('', $this->message->getCommand());
        $this->assertEquals('some text', $this->message->getText());
        $this->assertEquals('some text', $this->message->getText(true));

        // /command@bot
        $this->message = TestHelpers::getFakeMessageObject(['text' => '/help@testbot']);
        $this->assertEquals('/help@testbot', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals('/help@testbot', $this->message->getText());
        $this->assertEquals('', $this->message->getText(true));

        // /commmad text
        $this->message = TestHelpers::getFakeMessageObject(['text' => '/help some text']);
        $this->assertEquals('/help', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals('/help some text', $this->message->getText());
        $this->assertEquals('some text', $this->message->getText(true));

        // /command@bot some text
        $this->message = TestHelpers::getFakeMessageObject(['text' => '/help@testbot some text']);
        $this->assertEquals('/help@testbot', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals('/help@testbot some text', $this->message->getText());
        $this->assertEquals('some text', $this->message->getText(true));

        // /commmad\n text
        $this->message = TestHelpers::getFakeMessageObject(['text' => "/help\n some text"]);
        $this->assertEquals('/help', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals("/help\n some text", $this->message->getText());
        $this->assertEquals(' some text', $this->message->getText(true));

        // /command@bot\nsome text
        $this->message = TestHelpers::getFakeMessageObject(['text' => "/help@testbot\nsome text"]);
        $this->assertEquals('/help@testbot', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals("/help@testbot\nsome text", $this->message->getText());
        $this->assertEquals('some text', $this->message->getText(true));

        // /command@bot \nsome text
        $this->message = TestHelpers::getFakeMessageObject(['text' => "/help@testbot \nsome text"]);
        $this->assertEquals('/help@testbot', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals("/help@testbot \nsome text", $this->message->getText());
        $this->assertEquals("\nsome text", $this->message->getText(true));
     }
}
