<?php

namespace PhpTelegramBot\Core\Entities\TransactionPartner;

use PhpTelegramBot\Core\Contracts\Factory;
use PhpTelegramBot\Core\Entities\Entity;

/**
 * @method string getType() Type of the transaction partner
 */
abstract class TransactionPartner extends Entity implements Factory
{
    public const TYPE_FRAGMENT = 'fragment';

    public const TYPE_USER = 'user';

    public const TYPE_OTHER = 'other';

    public static function make(array $data): static
    {
        return match ($data['type']) {
            self::TYPE_FRAGMENT => new TransactionPartnerFragment($data),
            self::TYPE_USER     => new TransactionPartnerUser($data),
            self::TYPE_OTHER    => new TransactionPartnerOther($data),
        };
    }
}
