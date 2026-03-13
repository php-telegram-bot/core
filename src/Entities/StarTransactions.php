<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StarTransactions
 *
 * Contains a list of Telegram Star transactions.
 *
 * @link https://core.telegram.org/bots/api#startransactions
 *
 * @method StarTransaction[] getTransactions() The list of transactions
 *
 * @method $this setTransactions(StarTransaction[] $transactions) The list of transactions
 */
class StarTransactions extends Entity
{
    protected function subEntities(): array
    {
        return [
            'transactions' => [StarTransaction::class],
        ];
    }
}
