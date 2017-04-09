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

/**
 * Class ReplyToMessage
 *
 * @todo Is this even required?!
 */
class ReplyToMessage extends Message
{
    /**
     * ReplyToMessage constructor.
     *
     * @param array  $data
     * @param string $bot_username
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data, $bot_username = '')
    {
        //As explained in the documentation
        //Reply to message can't contain other reply to message entities
        unset($data['reply_to_message']);

        parent::__construct($data, $bot_username);
    }
}
