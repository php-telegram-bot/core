<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;

/**
 * Class InlineKeyboardButton
 *
 * @link https://core.telegram.org/bots/api#inlinekeyboardbutton
 *
 * @method string getText()              Label text on the button
 * @method string getUrl()               Optional. HTTP url to be opened when button is pressed
 * @method string getCallbackData()      Optional. Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes
 * @method string getSwitchInlineQuery() Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot's username and the specified inline query in the input field. Can be empty, in which case just the bot’s username will be inserted.
 */
class InlineKeyboardButton extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function validate()
    {
        $num_params = 0;

        foreach (['url', 'callback_data', 'switch_inline_query'] as $param) {
            if (!empty($this->getProperty($param))) {
                $num_params++;
            }
        }

        if ($num_params !== 1) {
            throw new TelegramException('You must use only one of these fields: url, callback_data, switch_inline_query!');
        }
    }
}
