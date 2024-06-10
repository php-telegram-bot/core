<?php

namespace PhpTelegramBot\Core\Entities\ChatBoostSource;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;
use PhpTelegramBot\Core\Entities\User;

/**
 * @method int       getGiveawayMessageId() Identifier of a message in the chat with the giveaway; the message could have been deleted already. May be 0 if the message isn't sent yet.
 * @method User|null getUser()              Optional. User that won the prize in the giveaway if any
 * @method bool      isUnclaimed()          Optional. True, if the giveaway was completed, but there was no user to win the prize
 */
class ChatBoostSourceGiveaway extends ChatBoostSource implements AllowsBypassingGet
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
            'is_unclaimed' => false,
        ];
    }
}
