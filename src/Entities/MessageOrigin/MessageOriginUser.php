<?php

namespace PhpTelegramBot\Core\Entities\MessageOrigin;

use PhpTelegramBot\Core\Entities\User;

/**
 * @method User getSenderUser() User that sent the message originally
 */
class MessageOriginUser extends MessageOrigin
{
    protected static function subEntities(): array
    {
        return [
            'sender_user' => User::class,
        ];
    }
}
