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

use Longman\TelegramBot\Entities\Games\CallbackGame;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\SwitchInlineQueryChosenChat;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Tests\Unit\TestCase;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @package         TelegramTest
 */
class InlineKeyboardButtonTest extends TestCase
{
    public function testInlineKeyboardButtonSuccess(): void
    {
        new InlineKeyboardButton(['text' => 'message', 'url' => 'url_value']);
        new InlineKeyboardButton(['text' => 'message', 'callback_data' => 'callback_data_value']);
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query' => 'switch_inline_query_value']);
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query' => '']); // Allow empty string.
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value']);
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query_current_chat' => '']); // Allow empty string.
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query_chosen_chat' => new SwitchInlineQueryChosenChat([])]); // Allow empty string.
        new InlineKeyboardButton(['text' => 'message', 'callback_game' => new CallbackGame([])]);
        new InlineKeyboardButton(['text' => 'message', 'pay' => true]);
        self::assertTrue(true);
    }

    public function testInlineKeyboardButtonCouldBe(): void
    {
        self::assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'url' => 'url_value']
        ));
        self::assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'callback_data' => 'callback_data_value']
        ));
        self::assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'switch_inline_query' => 'switch_inline_query_value']
        ));
        self::assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value']
        ));
        self::assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'switch_inline_query_chosen_chat' => new SwitchInlineQueryChosenChat([])]
        ));
        self::assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'callback_game' => new CallbackGame([])]
        ));
        self::assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'pay' => true]
        ));

        self::assertFalse(InlineKeyboardButton::couldBe(['no_text' => 'message']));
        self::assertFalse(InlineKeyboardButton::couldBe(['text' => 'message']));
        self::assertFalse(InlineKeyboardButton::couldBe(['url' => 'url_value']));
        self::assertFalse(InlineKeyboardButton::couldBe([
            'callback_data' => 'callback_data_value'
        ]));
        self::assertFalse(InlineKeyboardButton::couldBe([
            'switch_inline_query' => 'switch_inline_query_value'
        ]));
        self::assertFalse(InlineKeyboardButton::couldBe([
            'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value'
        ]));
        self::assertFalse(InlineKeyboardButton::couldBe([
            'switch_inline_query_chosen_chat' => new SwitchInlineQueryChosenChat([])
        ]));
        self::assertFalse(InlineKeyboardButton::couldBe(['callback_game' => new CallbackGame([])]));
        self::assertFalse(InlineKeyboardButton::couldBe(['pay' => true]));

        self::assertFalse(InlineKeyboardButton::couldBe([
            'url'                              => 'url_value',
            'callback_data'                    => 'callback_data_value',
            'switch_inline_query'              => 'switch_inline_query_value',
            'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value',
            'switch_inline_query_chosen_chat'  => new SwitchInlineQueryChosenChat([]),
            'callback_game'                    => new CallbackGame([]),
            'pay'                              => true,
        ]));
    }
}
