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
 * Class ChatMember
 *
 * @link https://core.telegram.org/bots/api#chatmember
 *
 * @method User   getUser()   Information about the user.
 * @method string getStatus() The member's status in the chat. Can be "creator", "administrator", "member", "left" or "kicked"
 */
class ChatMember extends Entity
{
    /**
     * {@inheritdoc}
     */
    public function subEntities()
    {
        return [
            'user' => User::class,
        ];
    }
}
