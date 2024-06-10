<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;

/**
 * @method string getId()         Unique identifier of the business connection
 * @method User   getUser()       Business account user that created the business connection
 * @method int    getUserChatId() Identifier of a private chat with the user who created the business connection. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method int    getDate()       Date the connection was established in Unix time
 * @method bool   getCanReply()   True, if the bot can act on behalf of the business account in chats that were active in the last 24 hours
 * @method bool   isEnabled()     True, if the connection is active
 */
class BusinessConnection extends Entity implements AllowsBypassingGet
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
            'is_enabled' => false,
        ];
    }
}
