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
 * @method bool getResizeKeyboard()  Optional. Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
 * @method bool getOneTimeKeyboard() Optional. Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be available, but clients will automatically display the usual letter-keyboard in the chat – the user can press a special button in the input field to see the custom keyboard again. Defaults to false.
 * @method bool getSelective()       Optional. Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
 *
 * @method $this setResizeKeyboard(bool $resize_keyboard)    Optional. Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
 * @method $this setOneTimeKeyboard(bool $one_time_keyboard) Optional. Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be available, but clients will automatically display the usual letter-keyboard in the chat – the user can press a special button in the input field to see the custom keyboard again. Defaults to false.
 * @method $this setSelective(bool $selective)               Optional. Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
 */
class Keyboard extends Entity
{
    /**
     * {@inheritdoc}
     */
    public function __construct($data = [])
    {
        $data = call_user_func_array([$this, 'createFromParams'], func_get_args());
        parent::__construct($data);
    }

    /**
     * If this keyboard is an inline keyboard.
     *
     * @return bool
     */
    public function isInlineKeyboard()
    {
        return $this instanceof InlineKeyboard;
    }

    /**
     * Get the proper keyboard button class for this keyboard.
     *
     * @return mixed
     */
    public function getKeyboardButtonClass()
    {
        return $this->isInlineKeyboard() ? InlineKeyboardButton::class : KeyboardButton::class;
    }

    /**
     * Get the type of keyboard, either "inline_keyboard" or "keyboard".
     *
     * @return string
     */
    public function getKeyboardType()
    {
        return $this->isInlineKeyboard() ? 'inline_keyboard' : 'keyboard';
    }

    /**
     * If no explicit keyboard is passed, try to create one from the parameters.
     *
     * @return array
     */
    protected function createFromParams()
    {
        /** @var KeyboardButton|InlineKeyboardButton $button_class */
        $button_class  = $this->getKeyboardButtonClass();
        $keyboard_type = $this->getKeyboardType();

        // If the inline_keyboard isn't set directly, try to create one from the arguments.
        $data = func_get_arg(0);
        if (!array_key_exists($keyboard_type, $data)) {
            $new_keyboard = [];
            foreach (func_get_args() as $row) {
                if (is_array($row)) {
                    $new_row = [];
                    if ($button_class::couldBe($row)) {
                        $new_row[] = new $button_class($row);
                    } else {
                        foreach ($row as $button) {
                            if ($button instanceof $button_class) {
                                $new_row[] = $button;
                            } elseif (!$this->isInlineKeyboard() || $button_class::couldBe($button)) {
                                $new_row[] = new $button_class($button);
                            }
                        }
                    }
                } else {
                    $new_row = [new $button_class($row)];
                }
                $new_keyboard[] = $new_row;
            }

            if (!empty($new_keyboard)) {
                $data = [$keyboard_type => $new_keyboard];
            }
        }

        return $data;
    }

    /**
     * Create a new row in keyboard and add buttons.
     *
     * @return $this
     */
    public function addRow()
    {
        $keyboard_type          = $this->getKeyboardType();
        $this->$keyboard_type[] = func_get_args();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function validate()
    {
        $keyboard = $this->getProperty('keyboard');

        if ($keyboard !== null) {
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

    /**
     * Hide the current custom keyboard and display the default letter-keyboard.
     *
     * @link https://core.telegram.org/bots/api#replykeyboardhide
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\Keyboard
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function hide(array $data = [])
    {
        return new static(array_merge(['keyboard' => null, 'hide_keyboard' => true, 'selective' => false], $data));
    }

    /**
     * Display a reply interface to the user (act as if the user has selected the bot's message and tapped 'Reply').
     *
     * @link https://core.telegram.org/bots/api#forcereply
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\Keyboard
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function forceReply(array $data = [])
    {
        return new static(array_merge(['keyboard' => null, 'force_reply' => true, 'selective' => false], $data));
    }
}
