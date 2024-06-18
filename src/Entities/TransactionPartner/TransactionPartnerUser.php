<?php

namespace PhpTelegramBot\Core\Entities\TransactionPartner;

use PhpTelegramBot\Core\Entities\User;

/**
 * @method User getUser() Information about the user
 */
class TransactionPartnerUser extends TransactionPartner
{
    public static function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}
