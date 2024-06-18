<?php

namespace PhpTelegramBot\Core\Entities\TransactionPartner;

use PhpTelegramBot\Core\Entities\RevenueWithdrawalState\RevenueWithdrawalState;

/**
 * @method RevenueWithdrawalState|null getWithdrawalState() Optional. State of the transaction if the transaction is outgoing
 */
class TransactionPartnerFragment extends TransactionPartner
{
    public static function subEntities(): array
    {
        return [
            'withdrawal_state' => RevenueWithdrawalState::class,
        ];
    }
}
