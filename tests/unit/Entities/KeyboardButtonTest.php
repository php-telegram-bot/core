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

use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class KeyboardButtonTest extends TestCase
{
    public function testKeyboardButtonNoTextFail()
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('You must add some text to the button!');
        new KeyboardButton([]);
    }

    public function testKeyboardButtonTooManyParametersFail()
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('You must use only one of these fields: request_contact, request_location!');
        new KeyboardButton(['text' => 'message', 'request_contact' => true, 'request_location' => true]);
    }

    public function testKeyboardButtonSuccess()
    {
        new KeyboardButton(['text' => 'message']);
        new KeyboardButton(['text' => 'message', 'request_contact' => true]);
        new KeyboardButton(['text' => 'message', 'request_location' => true]);
        $this->assertTrue(true);
    }

    public function testInlineKeyboardButtonCouldBe()
    {
        $this->assertTrue(KeyboardButton::couldBe(['text' => 'message']));
        $this->assertFalse(KeyboardButton::couldBe(['no_text' => 'message']));
    }

    public function testKeyboardButtonParameterSetting()
    {
        $button = new KeyboardButton('message');
        $this->assertSame('message', $button->getText());
        $this->assertEmpty($button->getRequestContact());
        $this->assertEmpty($button->getRequestLocation());

        $button->setText('new message');
        $this->assertSame('new message', $button->getText());

        $button->setRequestContact(true);
        $this->assertTrue($button->getRequestContact());
        $this->assertEmpty($button->getRequestLocation());

        $button->setRequestLocation(true);
        $this->assertEmpty($button->getRequestContact());
        $this->assertTrue($button->getRequestLocation());
    }
}
