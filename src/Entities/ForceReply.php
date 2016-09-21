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

/**
 * Class ForceReply
 *
 * @link https://core.telegram.org/bots/api#forcereply
 *
 * @method bool getForceReply() Shows reply interface to the user, as if they manually selected the bot‘s message and tapped 'Reply'
 * @method bool getSelective()  Optional. Use this parameter if you want to force reply from specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
 */
class ForceReply extends Entity
{
    /**
     * ForceReply constructor.
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['force_reply'] = true;
        parent::__construct($data);
    }
}
