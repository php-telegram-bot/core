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
     * @expectedExceptionMessage text is empty!
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
        new InlineKeyboardButton(['text' => 'message', 'url' => 'url_value', 'callback_data' => 'callback_data_value']);
    }

    public function testInlineKeyboardButtonSuccess()
    {
        new InlineKeyboardButton(['text' => 'message', 'url' => 'url_value']);
        new InlineKeyboardButton(['text' => 'message', 'callback_data' => 'callback_data_value']);
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query' => 'switch_inline_query_value']);
    }
}
