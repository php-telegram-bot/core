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
 * Class KeyboardButton
 *
 * @link https://core.telegram.org/bots/api#keyboardbutton
 *
 * @method string getText()            Text of the button. If none of the optional fields are used, it will be sent to the bot as a message when the button is pressed
 * @method bool   getRequestContact()  Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only
 * @method bool   getRequestLocation() Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only
 */
class KeyboardButton extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function validate()
    {
        if ($this->getRequestContact() && $this->getRequestLocation()) {
            throw new TelegramException('You must use only one of these fields: request_contact, request_location!');
        }
    }
}
