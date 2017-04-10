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

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class KeyboardButtonTest extends TestCase
{
    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     * @expectedExceptionMessage You must add some text to the button!
     */
    public function testKeyboardButtonNoTextFail()
    {
        new KeyboardButton([]);
    }

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     * @expectedExceptionMessage You must use only one of these fields: request_contact, request_location!
     */
    public function testKeyboardButtonTooManyParametersFail()
    {
        new KeyboardButton(['text' => 'message', 'request_contact' => true, 'request_location' => true]);
    }

    public function testKeyboardButtonSuccess()
    {
        new KeyboardButton(['text' => 'message']);
        new KeyboardButton(['text' => 'message', 'request_contact' => true]);
        new KeyboardButton(['text' => 'message', 'request_location' => true]);
    }

    public function testInlineKeyboardButtonCouldBe()
    {
        self::assertTrue(KeyboardButton::couldBe(['text' => 'message']));
        self::assertFalse(KeyboardButton::couldBe(['no_text' => 'message']));
    }

    public function testKeyboardButtonParameterSetting()
    {
        $button = new KeyboardButton('message');
        self::assertEmpty($button->getRequestContact());
        self::assertEmpty($button->getRequestLocation());

        $button->setRequestContact(true);
        self::assertTrue($button->getRequestContact());
        self::assertEmpty($button->getRequestLocation());

        $button->setRequestLocation(true);
        self::assertEmpty($button->getRequestContact());
        self::assertTrue($button->getRequestLocation());
    }
}
