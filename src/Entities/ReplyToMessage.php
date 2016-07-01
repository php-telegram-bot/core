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

class ReplyToMessage extends Message
{

    /**
     * ReplyToMessage constructor.
     *
     * @param array $data
     * @param $bot_name
     */
    public function __construct(array $data, $bot_name)
    {

        //As explained in the documentation
        //Reply to message can't contain other reply to message entities
        $reply_to_message = null;

        $this->init($data, $bot_name);
    }
}
