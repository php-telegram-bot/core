<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Written by Marco Boretto <marco.bore@gmail.com>
 */

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;


/**
 * Class ReplyKeyboardMarkup
 *
 * @link https://core.telegram.org/bots/api#replykeyboardmarkup
 *
 * @method KeyboardButton[][] getKeyboard()        Array of button rows, each represented by an Array of KeyboardButton objects
 * @method bool               getResizeKeyboard()  Optional. Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
 * @method bool               getOneTimeKeyboard() Optional. Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be available, but clients will automatically display the usual letter-keyboard in the chat – the user can press a special button in the input field to see the custom keyboard again. Defaults to false.
 * @method bool               getSelective()       Optional. Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
 */
class ReplyKeyboardMarkup extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'keyboard' => KeyboardButton::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate()
    {
        $keyboard = $this->getProperty('keyboard');

        if ($keyboard === null) {
            throw new TelegramException('Keyboard field is empty!');
        }

        if (!is_array($keyboard)) {
            throw new TelegramException('Keyboard field is not an array!');
        }

        foreach ($keyboard as $item) {
            if (!is_array($item)) {
                throw new TelegramException('Keyboard subfield is not an array!');
            }
        }
    }
}
