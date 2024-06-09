<?php

namespace PhpTelegramBot\Core\Entities\ChatBoostSource;

use PhpTelegramBot\Core\Entities\User;

/**
 * @method User getUser() User for which the gift code was created
 */
class ChatBoostSourceGiftCode extends ChatBoostSource
{
    protected static function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}
