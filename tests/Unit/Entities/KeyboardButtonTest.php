<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit\Entities;

use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Entities\KeyboardButtonPollType;
use Longman\TelegramBot\Entities\KeyboardButtonRequestChat;
use Longman\TelegramBot\Entities\KeyboardButtonRequestUser;
use Longman\TelegramBot\Entities\WebAppInfo;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Tests\Unit\TestCase;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @package         TelegramTest
 */
class KeyboardButtonTest extends TestCase
{
    public function testKeyboardButtonSuccess(): void
    {
        new KeyboardButton(['text' => 'message']);
        new KeyboardButton(['text' => 'message', 'request_user' => new KeyboardButtonRequestUser([])]);
        new KeyboardButton(['text' => 'message', 'request_chat' => new KeyboardButtonRequestChat([])]);
        new KeyboardButton(['text' => 'message', 'request_contact' => true]);
        new KeyboardButton(['text' => 'message', 'request_location' => true]);
        new KeyboardButton(['text' => 'message', 'request_poll' => new KeyboardButtonPollType([])]);
        new KeyboardButton(['text' => 'message', 'web_app' => new WebAppInfo([])]);
        self::assertTrue(true);
    }

    public function testInlineKeyboardButtonCouldBe(): void
    {
        self::assertTrue(KeyboardButton::couldBe(['text' => 'message']));
        self::assertFalse(KeyboardButton::couldBe(['no_text' => 'message']));
    }

    public function testReturnsSubentitiesOnArray()
    {
        $button = new KeyboardButton('message');
        $button->request_user = [];
        $this->assertInstanceOf(KeyboardButtonRequestUser::class, $button->getRequestUser());

        $button = new KeyboardButton('message');
        $button->request_chat = [];
        $this->assertInstanceOf(KeyboardButtonRequestChat::class, $button->getRequestChat());

        $button = new KeyboardButton('message');
        $button->request_poll = [];
        $this->assertInstanceOf(KeyboardButtonPollType::class, $button->getRequestPoll());

        $button = new KeyboardButton('message');
        $button->web_app = [];
        $this->assertInstanceOf(WebAppInfo::class, $button->getWebApp());
    }
}
