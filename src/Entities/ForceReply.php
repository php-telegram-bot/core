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

class ForceReply extends Entity
{
    protected $force_reply;
    protected $selective;

    public function __construct(array $data = null)
    {
        $this->force_reply = true;
        $this->selective = isset($data['selective']) ? $data['selective'] : false;
    }
}
