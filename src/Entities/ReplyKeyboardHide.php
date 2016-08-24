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

class ReplyKeyboardHide extends Entity
{
    /**
     * @var bool
     */
    protected $hide_keyboard;

    /**
     * @var bool
     */
    protected $selective;

    /**
     * ReplyKeyboardHide constructor.
     *
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        $this->hide_keyboard = true;
        $this->selective     = isset($data['selective']) ? $data['selective'] : false;
    }
}
