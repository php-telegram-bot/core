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

class ForceReply extends Entity
{
    /**
     * @var bool
     */
    protected $force_reply;

    /**
     * @var bool|mixed
     */
    protected $selective;

    /**
     * ForceReply constructor.
     *
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        $this->force_reply = true;
        $this->selective   = isset($data['selective']) ? $data['selective'] : false;
    }
}
