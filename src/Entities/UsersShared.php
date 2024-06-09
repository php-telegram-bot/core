<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method int          getRequestId() Identifier of the request
 * @method SharedUser[] getUsers()     Information about users shared with the bot.
 */
class UsersShared extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'users' => [SharedUser::class],
        ];
    }
}
