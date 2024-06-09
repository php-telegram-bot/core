<?php

namespace PhpTelegramBot\Core\Entities\ChatMember;

use PhpTelegramBot\Core\Entities\User;

/**
 * @method User        getUser()        Information about the user
 * @method bool        getIsAnonymous() True, if the user's presence in the chat is hidden
 * @method string|null getCustomTitle() Optional. Custom title for this user
 */
class ChatMemberOwner extends ChatMember
{
    protected static function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}
