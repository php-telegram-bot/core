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
 *
 * @method $this setText(string $text)                      Text of the button. If none of the optional fields are used, it will be sent to the bot as a message when the button is pressed
 * @method $this setRequestContact(bool $request_contact)   Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only
 * @method $this setRequestLocation(bool $request_location) Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only
 */
class KeyboardButton extends Entity
{
    /**
     * {@inheritdoc}
     */
    public function __construct($data)
    {
        if (is_string($data)) {
            $data = ['text' => $data];
        }
        parent::__construct($data);
    }

    /**
     * Check if the passed data array could be a KeyboardButton.
     *
     * @param array $data
     *
     * @return bool
     */
    public static function couldBe($data)
    {
        return is_array($data) && array_key_exists('text', $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function validate()
    {
        if ($this->getProperty('text', '') === '') {
            throw new TelegramException('You must add some text to the button!');
        }

        if ($this->getRequestContact() && $this->getRequestLocation()) {
            throw new TelegramException('You must use only one of these fields: request_contact, request_location!');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $args)
    {
        // Only 1 of these can be set, so clear the others when setting a new one.
        if (in_array($method, ['setRequestContact', 'setRequestLocation'], true)) {
            unset($this->request_contact, $this->request_location);
        }

        return parent::__call($method, $args);
    }
}
