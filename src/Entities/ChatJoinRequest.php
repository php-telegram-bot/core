<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method Chat                getChat()       Chat to which the request was sent
 * @method User                getFrom()       User that sent the join request
 * @method int                 getUserChatId() Identifier of a private chat with the user who sent the join request. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier. The bot can use this identifier for 5 minutes to send messages until the join request is processed, assuming no other administrator contacted the user.
 * @method int                 getDate()       Date the request was sent in Unix time
 * @method string|null         getBio()        Optional. Bio of the user.
 * @method ChatInviteLink|null getInviteLink() Optional. Chat invite link that was used by the user to send the join request
 */
class ChatJoinRequest extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'from' => User::class,
            'invite_link' => ChatInviteLink::class,
        ];
    }
}
