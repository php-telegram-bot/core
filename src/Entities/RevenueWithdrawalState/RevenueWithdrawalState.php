<?php

namespace PhpTelegramBot\Core\Entities\RevenueWithdrawalState;

use PhpTelegramBot\Core\Entities\Entity;
use PhpTelegramBot\Core\Entities\Factory;

/**
 * @method string getType() Type of the state
 */
abstract class RevenueWithdrawalState extends Entity implements Factory
{
    public const TYPE_PENDING = 'pending';

    public const TYPE_SUCCEEDED = 'succeeded';

    public const TYPE_FAILED = 'failed';

    public static function make(array $data): static
    {
        return match ($data['type']) {
            self::TYPE_PENDING   => new RevenueWithdrawalStatePending($data),
            self::TYPE_SUCCEEDED => new RevenueWithdrawalStateSucceeded($data),
            self::TYPE_FAILED    => new RevenueWithdrawalStateFailed($data),
        };
    }
}
