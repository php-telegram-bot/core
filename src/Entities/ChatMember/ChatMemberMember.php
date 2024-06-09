<?php

namespace PhpTelegramBot\Core\Entities\ChatMember;

use PhpTelegramBot\Core\Entities\User;

/**
 * @method User getUser() Information about the user
 */
class ChatMemberMember extends ChatMember
{
    protected static function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}
