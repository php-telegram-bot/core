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
 * Class InlineKeyboardMarkup
 *
 * @link https://core.telegram.org/bots/api#inlinekeyboardmarkup
 */
class InlineKeyboardMarkup extends Entity
{
    /**
     * @var array
     */
    protected $inline_keyboard;

    /**
     * InlineKeyboardMarkup constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct($data = array())
    {
        if (isset($data['inline_keyboard'])) {
            if (is_array($data['inline_keyboard'])) {
                foreach ($data['inline_keyboard'] as $item) {
                    if (!is_array($item)) {
                        throw new TelegramException('Inline Keyboard subfield is not an array!');
                    }
                }
                $this->inline_keyboard = $data['inline_keyboard'];
            } else {
                throw new TelegramException('Inline Keyboard field is not an array!');
            }
        } else {
            throw new TelegramException('Inline Keyboard field is empty!');
        }
    }
}
