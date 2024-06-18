<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Entities\TransactionPartner\TransactionPartner;

/**
 * @method string                  getId()       Unique identifier of the transaction. Coincides with the identifer of the original transaction for refund transactions. Coincides with SuccessfulPayment.telegram_payment_charge_id for successful incoming payments from users.
 * @method int                     getAmount()   Number of Telegram Stars transferred by the transaction
 * @method int                     getDate()     Date the transaction was created in Unix time
 * @method TransactionPartner|null getSource()   Optional. Source of an incoming transaction (e.g., a user purchasing goods or services, Fragment refunding a failed withdrawal). Only for incoming transactions
 * @method TransactionPartner|null getReceiver() Optional. Receiver of an outgoing transaction (e.g., a user for a purchase refund, Fragment for a withdrawal). Only for outgoing transactions
 */
class StarTransaction extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'source'   => TransactionPartner::class,
            'receiver' => TransactionPartner::class,
        ];
    }
}
