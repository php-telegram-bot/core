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

class ReplyKeyboardMarkup extends Entity
{
    /**
     * @var array
     */
    protected $keyboard;

    /**
     * @var bool
     */
    protected $resize_keyboard;

    /**
     * @var bool
     */
    protected $one_time_keyboard;

    /**
     * @var bool
     */
    protected $selective;

    /**
     * ReplyKeyboardMarkup constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct($data = array())
    {
        if (isset($data['keyboard'])) {
            if (is_array($data['keyboard'])) {
                foreach ($data['keyboard'] as $item) {
                    if (!is_array($item)) {
                        throw new TelegramException('Keyboard subfield is not an array!');
                    }
                }
                $this->keyboard = $data['keyboard'];
            } else {
                throw new TelegramException('Keyboard field is not an array!');
            }
        } else {
            throw new TelegramException('Keyboard field is empty!');
        }

        $this->resize_keyboard   = isset($data['resize_keyboard']) ? $data['resize_keyboard'] : false;
        $this->one_time_keyboard = isset($data['one_time_keyboard']) ? $data['one_time_keyboard'] : false;
        $this->selective         = isset($data['selective']) ? $data['selective'] : false;
    }
}
