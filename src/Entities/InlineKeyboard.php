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
 * Class InlineKeyboard
 *
 * @link https://core.telegram.org/bots/api#inlinekeyboardmarkup
 */
class InlineKeyboard extends Keyboard
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
     * {@inheritdoc}
     */
    protected function validate()
    {
        $inline_keyboard = $this->getProperty('inline_keyboard');

        if ($inline_keyboard !== null) {
            if (!is_array($inline_keyboard)) {
                throw new TelegramException('Inline Keyboard field is not an array!');
            }

            foreach ($inline_keyboard as $item) {
                if (!is_array($item)) {
                    throw new TelegramException('Inline Keyboard subfield is not an array!');
                }
            }
        }
    }
}
