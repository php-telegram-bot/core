<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method ChatBoost[] getBoosts() The list of boosts added to the chat by the user
 */
class UserChatBoosts extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'boosts' => [ChatBoost::class],
        ];
    }
}
