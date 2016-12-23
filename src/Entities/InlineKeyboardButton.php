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
 * @method string getText()                         Label text on the button
 * @method string getUrl()                          Optional. HTTP url to be opened when button is pressed
 * @method string getCallbackData()                 Optional. Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes
 * @method string getSwitchInlineQuery()            Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot's username and the specified inline query in the input field. Can be empty, in which case just the bot’s username will be inserted.
 * @method string getSwitchInlineQueryCurrentChat() Optional. If set, pressing the button will insert the bot‘s username and the specified inline query in the current chat's input field. Can be empty, in which case only the bot’s username will be inserted.
 *
 * @method $this setText(string $text)                                                     Label text on the button
 * @method $this setUrl(string $url)                                                       Optional. HTTP url to be opened when button is pressed
 * @method $this setCallbackData(string $callback_data)                                    Optional. Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes
 * @method $this setSwitchInlineQuery(string $switch_inline_query)                         Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot's username and the specified inline query in the input field. Can be empty, in which case just the bot’s username will be inserted.
 * @method $this setSwitchInlineQueryCurrentChat(string $switch_inline_query_current_chat) Optional. If set, pressing the button will insert the bot‘s username and the specified inline query in the current chat's input field. Can be empty, in which case only the bot’s username will be inserted.
 */
class InlineKeyboardButton extends KeyboardButton
{
    /**
     * Check if the passed data array could be an InlineKeyboardButton.
     *
     * @param array $data
     *
     * @return bool
     */
    public static function couldBe($data)
    {
        return is_array($data) &&
               array_key_exists('text', $data) && (
                   array_key_exists('url', $data) ||
                   array_key_exists('callback_data', $data) ||
                   array_key_exists('switch_inline_query', $data) ||
                   array_key_exists('switch_inline_query_current_chat', $data)
               );
    }

    /**
     * {@inheritdoc}
     */
    protected function validate()
    {
        if ($this->getProperty('text', '') === '') {
            throw new TelegramException('You must add some text to the button!');
        }

        $num_params = 0;

        foreach (['url', 'callback_data', 'switch_inline_query', 'switch_inline_query_current_chat'] as $param) {
            if (!empty($this->getProperty($param))) {
                $num_params++;
            }
        }

        if ($num_params !== 1) {
            throw new TelegramException('You must use only one of these fields: url, callback_data, switch_inline_query, switch_inline_query_current_chat!');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $args)
    {
        // Only 1 of these can be set, so clear the others when setting a new one.
        if (in_array($method, ['setUrl', 'setCallbackData', 'setSwitchInlineQuery', 'setSwitchInlineQueryCurrentChat'], true)) {
            unset($this->url, $this->callback_data, $this->switch_inline_query, $this->switch_inline_query_current_chat);
        }

        return parent::__call($method, $args);
    }
}
