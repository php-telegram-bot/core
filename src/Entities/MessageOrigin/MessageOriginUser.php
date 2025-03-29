<?php

namespace Longman\TelegramBot\Entities\MessageOrigin;

use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\User;

/**
 * The message was originally sent by a known user.
 *
 * @link https://core.telegram.org/bots/api#messageoriginuser
 *
 * @method string getType()       Type of the message origin, always “user”
 * @method int    getDate()       Date the message was sent originally in Unix time
 * @method User   getSenderUser() User that sent the message originally
 */
class MessageOriginUser extends Entity implements MessageOrigin
{
    protected function subEntities(): array
    {
        return [
            'sender_user' => User::class,
        ];
    }
}
