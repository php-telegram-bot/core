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

use Longman\TelegramBot\Entities\Games\CallbackGame;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class InlineKeyboardButtonTest extends TestCase
{
    public function testInlineKeyboardButtonNoTextFail()
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('You must add some text to the button!');
        new InlineKeyboardButton([]);
    }

    public function testInlineKeyboardButtonNoParameterFail()
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('You must use only one of these fields: url, login_url, callback_data, switch_inline_query, switch_inline_query_current_chat, callback_game, pay!');
        new InlineKeyboardButton(['text' => 'message']);
    }

    public function testInlineKeyboardButtonTooManyParametersFail()
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('You must use only one of these fields: url, login_url, callback_data, switch_inline_query, switch_inline_query_current_chat, callback_game, pay!');
        $test_funcs = [
            function () {
                new InlineKeyboardButton([
                    'text'          => 'message',
                    'url'           => 'url_value',
                    'callback_data' => 'callback_data_value',
                ]);
            },
            function () {
                new InlineKeyboardButton([
                    'text'                => 'message',
                    'url'                 => 'url_value',
                    'switch_inline_query' => 'switch_inline_query_value',
                ]);
            },
            function () {
                new InlineKeyboardButton([
                    'text'                => 'message',
                    'callback_data'       => 'callback_data_value',
                    'switch_inline_query' => 'switch_inline_query_value',
                ]);
            },
            function () {
                new InlineKeyboardButton([
                    'text'                             => 'message',
                    'callback_data'                    => 'callback_data_value',
                    'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value',
                ]);
            },
            function () {
                new InlineKeyboardButton([
                    'text'          => 'message',
                    'callback_data' => 'callback_data_value',
                    'callback_game' => new CallbackGame([]),
                ]);
            },
            function () {
                new InlineKeyboardButton([
                    'text'          => 'message',
                    'callback_data' => 'callback_data_value',
                    'pay'           => true,
                ]);
            },
        ];

        $test_funcs[array_rand($test_funcs)]();
    }

    public function testInlineKeyboardButtonSuccess()
    {
        new InlineKeyboardButton(['text' => 'message', 'url' => 'url_value']);
        new InlineKeyboardButton(['text' => 'message', 'callback_data' => 'callback_data_value']);
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query' => 'switch_inline_query_value']);
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query' => '']); // Allow empty string.
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value']);
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query_current_chat' => '']); // Allow empty string.
        new InlineKeyboardButton(['text' => 'message', 'callback_game' => new CallbackGame([])]);
        new InlineKeyboardButton(['text' => 'message', 'pay' => true]);
        $this->assertTrue(true);
    }

    public function testInlineKeyboardButtonCouldBe()
    {
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'url' => 'url_value']
        ));
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'callback_data' => 'callback_data_value']
        ));
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'switch_inline_query' => 'switch_inline_query_value']
        ));
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value']
        ));
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'callback_game' => new CallbackGame([])]
        ));
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'pay' => true]
        ));

        $this->assertFalse(InlineKeyboardButton::couldBe(['no_text' => 'message']));
        $this->assertFalse(InlineKeyboardButton::couldBe(['text' => 'message']));
        $this->assertFalse(InlineKeyboardButton::couldBe(['url' => 'url_value']));
        $this->assertFalse(InlineKeyboardButton::couldBe(
            ['callback_data' => 'callback_data_value']
        ));
        $this->assertFalse(InlineKeyboardButton::couldBe(
            ['switch_inline_query' => 'switch_inline_query_value']
        ));
        $this->assertFalse(InlineKeyboardButton::couldBe(['callback_game' => new CallbackGame([])]));
        $this->assertFalse(InlineKeyboardButton::couldBe(['pay' => true]));

        $this->assertFalse(InlineKeyboardButton::couldBe([
            'url'                              => 'url_value',
            'callback_data'                    => 'callback_data_value',
            'switch_inline_query'              => 'switch_inline_query_value',
            'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value',
            'callback_game'                    => new CallbackGame([]),
            'pay'                              => true,
        ]));
    }

    public function testInlineKeyboardButtonParameterSetting()
    {
        $button = new InlineKeyboardButton(['text' => 'message', 'url' => 'url_value']);
        $this->assertSame('url_value', $button->getUrl());
        $this->assertEmpty($button->getCallbackData());
        $this->assertEmpty($button->getSwitchInlineQuery());
        $this->assertEmpty($button->getSwitchInlineQueryCurrentChat());
        $this->assertEmpty($button->getCallbackGame());
        $this->assertEmpty($button->getPay());

        $button->setCallbackData('callback_data_value');
        $this->assertEmpty($button->getUrl());
        $this->assertSame('callback_data_value', $button->getCallbackData());
        $this->assertEmpty($button->getSwitchInlineQuery());
        $this->assertEmpty($button->getSwitchInlineQueryCurrentChat());
        $this->assertEmpty($button->getCallbackGame());
        $this->assertEmpty($button->getPay());

        $button->setSwitchInlineQuery('switch_inline_query_value');
        $this->assertEmpty($button->getUrl());
        $this->assertEmpty($button->getCallbackData());
        $this->assertSame('switch_inline_query_value', $button->getSwitchInlineQuery());
        $this->assertEmpty($button->getSwitchInlineQueryCurrentChat());
        $this->assertEmpty($button->getCallbackGame());
        $this->assertEmpty($button->getPay());

        $button->setSwitchInlineQueryCurrentChat('switch_inline_query_current_chat_value');
        $this->assertEmpty($button->getUrl());
        $this->assertEmpty($button->getCallbackData());
        $this->assertEmpty($button->getSwitchInlineQuery());
        $this->assertSame('switch_inline_query_current_chat_value', $button->getSwitchInlineQueryCurrentChat());
        $this->assertEmpty($button->getCallbackGame());
        $this->assertEmpty($button->getPay());

        $button->setCallbackGame($callback_game = new CallbackGame([]));
        $this->assertEmpty($button->getUrl());
        $this->assertEmpty($button->getCallbackData());
        $this->assertEmpty($button->getSwitchInlineQuery());
        $this->assertEmpty($button->getSwitchInlineQueryCurrentChat());
        $this->assertSame($callback_game, $button->getCallbackGame());
        $this->assertEmpty($button->getPay());

        $button->setPay(true);
        $this->assertEmpty($button->getUrl());
        $this->assertEmpty($button->getCallbackData());
        $this->assertEmpty($button->getSwitchInlineQuery());
        $this->assertEmpty($button->getSwitchInlineQueryCurrentChat());
        $this->assertEmpty($button->getCallbackGame());
        $this->assertTrue($button->getPay());
    }
}
