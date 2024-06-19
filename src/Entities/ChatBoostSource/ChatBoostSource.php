<?php

namespace PhpTelegramBot\Core\Entities\ChatBoostSource;

use PhpTelegramBot\Core\Contracts\Factory;
use PhpTelegramBot\Core\Entities\Entity;

/**
 * @method string getSource() Source of the boost
 */
class ChatBoostSource extends Entity implements Factory
{
    public const SOURCE_PREMIUM = 'premium';

    public const SOURCE_GIFT_CODE = 'gift_code';

    public const SOURCE_GIVEAWAY = 'giveaway';

    public static function make(array $data): static
    {
        return match ($data['source']) {
            self::SOURCE_PREMIUM   => new ChatBoostSourcePremium($data),
            self::SOURCE_GIFT_CODE => new ChatBoostSourceGiftCode($data),
            self::SOURCE_GIVEAWAY  => new ChatBoostSourceGiveaway($data),
        };
    }
}
