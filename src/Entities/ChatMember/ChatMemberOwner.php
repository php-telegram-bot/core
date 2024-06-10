<?php

namespace PhpTelegramBot\Core\Entities\ChatMember;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;
use PhpTelegramBot\Core\Entities\User;

/**
 * @method User        getUser()        Information about the user
 * @method bool        isAnonymous()    True, if the user's presence in the chat is hidden
 * @method string|null getCustomTitle() Optional. Custom title for this user
 */
class ChatMemberOwner extends ChatMember implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'is_anonymous' => false,
        ];
    }
}
