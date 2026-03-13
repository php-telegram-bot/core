<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class TransactionPartnerFragment
 *
 * Describes a transaction with Fragment.
 *
 * @link https://core.telegram.org/bots/api#transactionpartnerfragment
 *
 * @method string             getType()            Type of the transaction partner, always “fragment”
 * @method RevenueWithdrawalState getWithdrawalState() Optional. State of the transaction if the transaction is a withdrawal of Telegram Stars to Fragment
 */
class TransactionPartnerFragment extends Entity implements TransactionPartner
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'withdrawal_state' => RevenueWithdrawalState::class,
        ];
    }
}
