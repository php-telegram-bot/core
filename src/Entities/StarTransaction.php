<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StarTransaction
 *
 * Describes a Telegram Star transaction.
 *
 * @link https://core.telegram.org/bots/api#startransaction
 *
 * @method string             getId()             Unique identifier of the transaction. Coincides with the identifier of the original transaction for refund transactions.
 * @method int                getAmount()         Number of Telegram Stars transferred by the transaction
 * @method int                getNanostarAmount() Optional. The number of 1/10^9 units of Telegram Stars transferred by the transaction; for affiliate program commissions only.
 * @method int                getDate()           Date the transaction was created in Unix time
 * @method TransactionPartner getSource()         Optional. Source of the transaction. Can be a user, a bot, or an app. Null for refund transactions.
 * @method TransactionPartner getReceiver()       Optional. Receiver of the transaction. Can be a user, a bot, or an app. Null for refund transactions.
 */
class StarTransaction extends Entity
{
    protected function subEntities(): array
    {
        return [
            'source'   => TransactionPartnerFactory::class,
            'receiver' => TransactionPartnerFactory::class,
        ];
    }
}
