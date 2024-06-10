<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;

/**
 * @method int         getId()                    Unique identifier for this user or bot. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method bool        isBot()                    True, if this user is a bot
 * @method string      getFirstName()             User's or bot's first name
 * @method string|null getLastName()              Optional. User's or bot's last name
 * @method string|null getUsername()              Optional. User's or bot's username
 * @method string|null getLanguageCode()          Optional. IETF language tag of the user's language
 * @method bool        isPremium()                Optional. True, if this user is a Telegram Premium user
 * @method true|null   getAddedToAttachmentMenu() Optional. True, if this user added the bot to the attachment menu
 * @method bool        canJoinGroups()            Optional. True, if the bot can be invited to groups. Returned only in getMe.
 * @method bool        canReadAllGroupMessages()  Optional. True, if privacy mode is disabled for the bot. Returned only in getMe.
 * @method bool|null   getSupportsInlineQueries() Optional. True, if the bot supports inline queries. Returned only in getMe.
 * @method bool        canConnectToBusiness()     Optional. True, if the bot can be connected to a Telegram Business account to receive its messages. Returned only in getMe.
 */
class User extends Entity implements AllowsBypassingGet
{
    public static function fieldsBypassingGet(): array
    {
        return [
            'is_bot'                      => false,
            'is_premium'                  => false,
            'can_join_groups'             => false,
            'can_read_all_group_messages' => false,
            'can_connect_to_business'     => false,
        ];
    }
}
