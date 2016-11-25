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

use Longman\TelegramBot\Entities\InlineKeyboardButton;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class InlineKeyboardButtonTest extends TestCase
{
    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     * @expectedExceptionMessage You must add some text to the button!
     */
    public function testInlineKeyboardButtonNoTextFail()
    {
        new InlineKeyboardButton([]);
    }

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     * @expectedExceptionMessage You must use only one of these fields: url, callback_data, switch_inline_query!
     */
    public function testInlineKeyboardButtonNoParameterFail()
    {
        new InlineKeyboardButton(['text' => 'message']);
    }

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     * @expectedExceptionMessage You must use only one of these fields: url, callback_data, switch_inline_query!
     */
    public function testInlineKeyboardButtonTooManyParametersFail()
    {
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
        ];

        $test_funcs[array_rand($test_funcs)]();
    }

    public function testInlineKeyboardButtonSuccess()
    {
        new InlineKeyboardButton(['text' => 'message', 'url' => 'url_value']);
        new InlineKeyboardButton(['text' => 'message', 'callback_data' => 'callback_data_value']);
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query' => 'switch_inline_query_value']);
    }

    public function testInlineKeyboardButtonCouldBe()
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

        self::assertFalse(InlineKeyboardButton::couldBe(['no_text' => 'message']));
        self::assertFalse(InlineKeyboardButton::couldBe(['text' => 'message']));
        self::assertFalse(InlineKeyboardButton::couldBe(['url' => 'url_value']));
        self::assertFalse(InlineKeyboardButton::couldBe(
            ['callback_data' => 'callback_data_value']
        ));
        self::assertFalse(InlineKeyboardButton::couldBe(
            ['switch_inline_query' => 'switch_inline_query_value']
        ));
        self::assertFalse(InlineKeyboardButton::couldBe([
            'url'                 => 'url_value',
            'callback_data'       => 'callback_data_value',
            'switch_inline_query' => 'switch_inline_query_value',
        ]));
    }

    public function testInlineKeyboardButtonParameterSetting()
    {
        $button = new InlineKeyboardButton(['text' => 'message', 'url' => 'url_value']);
        self::assertSame('url_value', $button->getUrl());
        self::assertEmpty($button->getCallbackData());
        self::assertEmpty($button->getSwitchInlineQuery());

        $button->setCallbackData('callback_data_value');
        self::assertEmpty($button->getUrl());
        self::assertSame('callback_data_value', $button->getCallbackData());
        self::assertEmpty($button->getSwitchInlineQuery());

        $button->setSwitchInlineQuery('switch_inline_query_value');
        self::assertEmpty($button->getUrl());
        self::assertEmpty($button->getCallbackData());
        self::assertSame('switch_inline_query_value', $button->getSwitchInlineQuery());
    }
}
